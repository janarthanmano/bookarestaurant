
## Backend (Laravel API)

### Technologies Used

*   **Laravel**: A PHP framework for building web applications.
*   **SQLite:** Database for storing data

### Setup

1.  **Clone the repository:**
    ```bash
    mkdir bookarestaurant
    cd bookarestaurant
    git clone https://github.com/janarthanmano/bookarestaurant.git .
    ```
2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    npm run dev
    ```
3.  **Set up environment variables:**
    *   Create a `.env` file by copying `.env.example`
        ```bash
        cp .env.example .env
        ```
    *   Update the `.env` with your database credentials and other environment variables.
4.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```
5.  **Start the development server:**
    ```bash
    php artisan serve
    ```

    This will start the API server, typically accessible at `http://localhost:8000`.

### Fetch API data

*   **`php artisan get:menus`** run this command to fetch the data from the yhangry API endpoint and populate the database.

### API Endpoints

*   **`GET /api/set-menus`**: Fetches set menus data.
    *   Supports pagination using `page` parameter.
    *   Supports filtering by cuisine using `cuisineSlug` parameter.
*   **Response Format:**
```json
    {
      "filters": {
        "cuisines": [
          {
            "name": "Brunch",
            "slug": "brunch",
            "number_of_orders": 32,
            "set_menus_count": 1
          },
           ...
        ]
      },
      "setMenus": [
        {
          "name": "Desi Culinary Extravaganza",
          "description": "Want a Michelin star experience ?",
          "price": "57.00",
          "minSpend": "250.00",
          "thumbnail": "https://d3gko8938onrvn.cloudfront.net/setmenus/thumbnails/07a57c09c5d93d3dcfb09e2cab5b5af7.webp",
          "cuisines": [
            {
              "name": "Indian",
              "slug": "indian"
            }
          ]
        },
          ...
      ],
      "links": {
        "first": "http://yhangry.test/api/set-menus?cuisineSlug=indian&page=1",
        "last": "http://yhangry.test/api/set-menus?cuisineSlug=indian&page=1",
        "prev": null,
        "next": null
      },
      "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
          {
            "url": null,
            "label": "« Previous",
            "active": false
          },
          {
            "url": "http://yhangry.test/api/set-menus?cuisineSlug=indian&page=1",
            "label": "1",
            "active": true
          },
          {
            "url": null,
            "label": "Next »",
            "active": false
          }
        ],
        "path": "http://yhangry.test/api/set-menus",
        "per_page": 20,
        "to": 7,
        "total": 7
      }
    }
   ```


## Frontend (React)

### Technologies Used

*   **React**: A JavaScript library for building user interfaces.
*   **Axios**: For making HTTP requests to the backend API.
*   **Tailwind CSS:** For styling

### Key Features

*   **Set Menu Display:** Displays a list of set menus with images, descriptions, prices, and cuisines.
*   **Cuisine Filtering:** Allows users to filter set menus by cuisine type.
*   **Infinite Scrolling:** Automatically loads more menus as the user scrolls down the page.
*   **"Back to Top" Button:**  A button at the bottom right to scroll smoothly to the top of the page.

### Components

*   **`SetMenus`**: The main component that fetches and displays set menu data.
*  **Cuisine Filtering**: Allows users to filter by cuisine
*  **Infinite Scrolling**: Uses the `useRef` and scroll event listener to load more menus.

### Code Structure

*   **`resources/js/Pages/SetMenus.js`**: The main component with the infinite scroll, filtering, and displaying menus.
