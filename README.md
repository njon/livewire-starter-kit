
<p align="center"><a href="https://lunarphp.io/" target="_blank"><img src="https://raw.githubusercontent.com/lunarphp/art/main/lunar-logo.svg" width="200" alt="Lunar"></a></p>

# Starter Kit

This repository is provided as a reference to learn how to use Lunar Laravel E-Commerce package. This example is a classic e-commerce store.

> **Warning**
> This application is purely an example of how you can implement Lunar headless e-commerce for Laravel. It is not production ready or complete.

# Installation

For full installation instructions please visit https://docs.lunarphp.io/core/starter-kits.html

## Installation with Docker

> Make sure you have Docker installed on your local machine.

### Environment Demo store

You can execute it via the `docker compose up` command in your favorite terminal. 
Please note that the speed of building images and initializing containers depends on your local machine and internet connection - it may take some time. 

```bash
cp .env.docker.example .env
docker-compose up
```

The demo store will be available to `http://localhost` in your browser.

####  Log into Lunar panel

Once the project is prepared, the Lunar panel will start and available to `http://localhost/lunar`. 

Default admin user is username `admin@lunarphp.io` and password `password`

@todo check if product discount will be showed if no discount available

//  Discounts
Remove column AmountOff also in edit/create Discount Type is always AmountOff. put inside controller to save directly without user input
STATUS should be based on start and end date. If between these dates always should be displayed as active.
Also for now remove discount based on amount and leave only percentage. Replace text with select type: 5%, 10%, 15%, 20%, 25%, 30%, 35%, 40%, 50% or select custom discount, if yes, show field to enter number.