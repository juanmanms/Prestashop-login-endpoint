# Authentication API Module for PrestaShop

## Overview

The Authentication API module for PrestaShop provides an API for user authentication. This module allows external applications to authenticate users against the PrestaShop database.

## Features

- User login via API
- Token-based authentication
- Secure password handling
- Easy integration with external applications

## Installation

1. Download the module package.
2. Upload the module to the `/modules` directory of your PrestaShop installation.
3. Go to the PrestaShop admin panel and navigate to `Modules` > `Module Manager`.
4. Find the Authentication API module and click `Install`.
5. You can now use the endpoint

## Usage

### API Endpoints

- **Login**: `/module/authenticationapi/authenticate`
  - Method: `POST`
  - Parameters: `email`, `password`
  - Response: `token`, `expiry_date`

### Example Request

```bash
curl -X POST https://your-prestashop-site.com/module/authenticationapi/authenticate \
    -d 'email=user@example.com&password=yourpassword'
```

### Example Response

```json
{
  "token": "your-authentication-token",
  "expiry_date": "2023-12-31T23:59:59Z", //puedes configurar el rango de tiempo
  "Profile employed": 3
}
```

## Support

For any issues or questions, please contact our support team at juanmanms@gmail.com

## License

This module is licensed under the MIT License. See the LICENSE file for more details.
