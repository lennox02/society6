# society6 Backend Test
To test:
1. Download Laravel project into a local environment.
2. Make copy of .env.example as .env file.  Modify your MySQL settings as needed (make sure your local environment has an available empty db).
3. Run in project directory of command line:
`php artisan migrate:fresh --seed`
4. Download Postman and install the collection json file that is in the root of this project.
5. Alter Postman url's to match your own local environment
6. The Postman Collection should be in order per the demo guidelines:
    * Create 2 creatives, at least one of them selling a fine art print, and at least 1 selling a t-shirt
    * Place an order that purchases a t-shirt and a fine art print
    * Make an API call as the vendor Marco Fine Arts to retrieve any fine art prints that have been ordered
    * Make an API call as the vendor DreamJunction to retrieve any t-shirts that have been ordered
    * Make an API call as each vendor to notify of shipment
