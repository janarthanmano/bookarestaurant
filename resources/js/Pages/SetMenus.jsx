import React, { useState, useEffect, useRef, useCallback } from 'react';
import axios from 'axios';

const SetMenus = () => {
    const [allData, setAllData] = useState({ filters: { cuisines: [] }, setMenus: [], links: {}, meta: {} });
    const [filteredData, setFilteredData] = useState({ setMenus: [], links: {}, meta: {} });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedCuisine, setSelectedCuisine] = useState(null);
    const [numberOfGuests, setNumberOfGuests] = useState(1);
    const [allMenusLoaded, setAllMenusLoaded] = useState(false);
    const [cuisineMenuCounts, setCuisineMenuCounts] = useState({});
    const [visibleMenuCount, setVisibleMenuCount] = useState(6);
    const [currentPage, setCurrentPage] = useState(1);
    const loadingRef = useRef(null);
    const [isFetching, setIsFetching] = useState(false);
    const [showScrollToTop, setShowScrollToTop] = useState(false);


    const apiURL = `/api/set-menus`;
    const fetchNextMenuPage = useCallback(async () => {
        try {
            const response = await axios.get(`${apiURL}?page=${currentPage + 1}`);
            if (response && response.data && response.data.setMenus) {
                return response.data.setMenus;
            } else {
                return null;
            }
        } catch (err) {
            setError("Error Loading additional menus, please try again later");
            console.error("API Error:", err);
            return null;
        }
    }, [apiURL, currentPage]);

    const fetchNextFilteredMenuPage = useCallback(async () => {
        try {
            const response = await axios.get(`${apiURL}?page=${currentPage + 1}&cuisineSlug=${selectedCuisine}`);
            if (response && response.data && response.data.setMenus) {
                return response.data.setMenus;
            } else {
                return null;
            }
        } catch (err) {
            setError("Error Loading additional menus, please try again later");
            console.error("API Error:", err);
            return null;
        }
    }, [apiURL, selectedCuisine, currentPage]);
    const loadMoreMenus = useCallback(async () => {
        if (isFetching) return;
        setIsFetching(true);
        if (!selectedCuisine) {
            const nextMenus = await fetchNextMenuPage();
            if (nextMenus) {
                setFilteredData((prevData) => ({
                    ...prevData,
                    setMenus: [...prevData.setMenus, ...nextMenus],
                }));
                setVisibleMenuCount(prevCount => prevCount + 6);
                setCurrentPage(prev => prev + 1);
            }
        } else {
            const nextMenus = await fetchNextFilteredMenuPage();
            if (nextMenus) {
                setFilteredData((prevData) => ({
                    ...prevData,
                    setMenus: [...prevData.setMenus, ...nextMenus],
                }));
                setVisibleMenuCount(prevCount => prevCount + 6);
                setCurrentPage(prev => prev + 1);
            }
        }
        setIsFetching(false);
    }, [selectedCuisine, currentPage, fetchNextMenuPage, fetchNextFilteredMenuPage, isFetching]);
    useEffect(() => {
        const fetchAllSetMenus = async () => {
            setLoading(true);
            setError(null);
            let allMenus = [];
            let currentPage = 1;
            try {
                while (true) {
                    const response = await axios.get(`${apiURL}?page=${currentPage}`);
                    if (response && response.data && response.data.setMenus) {
                        const { setMenus, links } = response.data;
                        allMenus = allMenus.concat(setMenus);
                        const data = {
                            ...response.data,
                            setMenus: allMenus
                        };
                        setAllData(data);
                        setFilteredData(data);
                        setAllMenusLoaded(true);
                        if (!links.next) break;
                        currentPage++;
                    } else {
                        setError("Error loading initial set menus from the API");
                        setLoading(false);
                        return;
                    }
                }
            } catch (err) {
                setError('Error loading set menus. Please try again later.');
                console.error("API Error:", err);
                setLoading(false);
            }
            setLoading(false);
        };
        fetchAllSetMenus();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);
    useEffect(() => {
        calculateCuisineMenuCounts();
    }, [allData]);
    useEffect(() => {
        if (allMenusLoaded) {
            filterMenus();
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [selectedCuisine]);

    useEffect(() => {
        const handleScroll = () => {
            if (loadingRef.current && allMenusLoaded) {
                const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
                if (scrollHeight - scrollTop <= clientHeight + 500) {
                    loadMoreMenus();
                }
                if(scrollTop > 200) {
                    setShowScrollToTop(true)
                } else {
                    setShowScrollToTop(false)
                }
            }
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [loadingRef, allMenusLoaded,loadMoreMenus]);
    const calculateCuisineMenuCounts = () => {
        if (!allData.filters || !allData.setMenus) return;
        const counts = {};
        allData.filters.cuisines.forEach((cuisine) => {
            counts[cuisine.slug] = allData.setMenus.filter((menu) =>
                menu.cuisines.some((c) => c.slug === cuisine.slug)
            ).length;
        });
        setCuisineMenuCounts(counts);
    };
    const filterMenus = () => {
        if (!selectedCuisine) {
            setFilteredData(allData);
            setVisibleMenuCount(6);
            setCurrentPage(1);
            return;
        }
        const filteredMenus = allData.setMenus.filter((menu) =>
            menu.cuisines.some((cuisine) => cuisine.slug === selectedCuisine)
        );
        setFilteredData({
            ...allData,
            setMenus: filteredMenus,
        });
        setVisibleMenuCount(6);
        setCurrentPage(1)

    };
    const handleCuisineFilter = (cuisine) => {
        setSelectedCuisine(cuisine === selectedCuisine ? null : cuisine);
    };
    const handleGuestChange = (event) => {
        const newGuestCount = parseInt(event.target.value, 10);
        setNumberOfGuests(isNaN(newGuestCount) || newGuestCount < 1 ? 1 : newGuestCount);
    };
    const calculateTotalPrice = (pricePerPerson, minimumSpend, numberOfGuests) => {
        const totalPrice = parseFloat(pricePerPerson) * numberOfGuests;
        return Math.max(totalPrice, parseFloat(minimumSpend)).toFixed(2);
    };
    const renderCuisines = () => {
        return (
            <ul className="flex flex-wrap gap-2 mb-4">
                <li
                    key="all"
                    className={`cursor-pointer px-4 py-2 rounded ${
                        selectedCuisine === null ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'
                    }`}
                    onClick={() => handleCuisineFilter(null)}
                >
                    Show All
                </li>
                {allData.filters.cuisines.map((cuisine) => (
                    <li
                        key={cuisine.slug}
                        className={`cursor-pointer px-4 py-2 rounded ${
                            selectedCuisine === cuisine.slug ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'
                        }`}
                        onClick={() => handleCuisineFilter(cuisine.slug)}
                    >
                        {cuisine.name} ({cuisineMenuCounts[cuisine.slug] || 0})
                    </li>
                ))}
            </ul>
        );
    };
    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
    if (loading) {
        return <div>Loading...</div>;
    }
    if (error) {
        return <div>Error: {error}</div>;
    }
    const menusToShow = filteredData.setMenus ? filteredData.setMenus.slice(0, visibleMenuCount) : [];
    return (
        <div className="container mx-auto p-4 relative" >
            <h1 className="text-3xl font-bold mb-4">Set Menus</h1>
            <div className="mb-4">
                <label htmlFor="guests" className="mr-2">Number of Guests:</label>
                <input
                    type="number"
                    id="guests"
                    className="border p-2 rounded"
                    value={numberOfGuests}
                    onChange={handleGuestChange}
                    min="1"
                />
            </div>
            {renderCuisines()}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {menusToShow.map((menu, index) => (
                    <div key={menu.name} className="bg-white shadow rounded p-4">
                        <h2 className="text-xl font-semibold mb-2">{menu.name}</h2>
                        <img src={menu.thumbnail} alt={menu.name} className="mb-2 rounded h-32 w-full object-cover" />
                        <p className="text-gray-700 mb-2">{menu.description}</p>
                        <div className="mt-2 text-sm">
                            Cuisines: {menu.cuisines.map(c => c.name).join(', ')}
                        </div>
                        <p className="mt-2">
                            Price per person: £{menu.price}
                        </p>
                        <p className="mt-2">
                            Total Price: £{calculateTotalPrice(menu.price, menu.minSpend, numberOfGuests)}
                        </p>
                        {menusToShow.length - 1 === index && <div ref={loadingRef}></div>}
                    </div>
                ))}
                {isFetching && <div>Loading more menus...</div>}
            </div>
            {showScrollToTop && (
                <button
                    onClick={scrollToTop}
                    className="fixed bottom-4 right-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Back to Top
                </button>
            )}
        </div>
    );
};

export default SetMenus;
