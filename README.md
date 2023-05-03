# shpping-cart-example

This project used Symfony 4.4  and MySQL

**Run following command to run project:**
`docker-compose up -d --no-deps --build`
`composer install`
`php bin/console doctrine:migrations:migrate`
`php bin/console doctrine:fixtures:load`

**Run Application**
`symfony server:start`
Application run on `http://127.0.0.1:8000`

**Admin**
Username: admin@example.com
Password: 123456

**User**
Username: user@example.com
Password: 123456

**Features**

1) Login and Register
2) Manager Products (Admin)
3) Add products to cart and place order by credits
4) Order Mail
5) View Orders
6) Search products
7) Manage users