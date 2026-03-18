# Dynamic Pricing System: A Comprehensive E-Commerce Platform

## Executive Summary

This repository contains a sophisticated **Dynamic Pricing System** designed for modern e-commerce platforms. The system implements real-time price optimization algorithms that adjust product prices based on multiple market factors including inventory levels, demand patterns, temporal fluctuations, and currency exchange rates. Built with a Model-View-Controller (MVC) architecture, this system provides both buyer and seller interfaces with comprehensive analytics and inventory management capabilities.

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Architectural Design](#architectural-design)
3. [Technical Stack](#technical-stack)
4. [Core Features](#core-features)
5. [Installation and Setup](#installation-and-setup)
6. [Pricing Algorithm](#pricing-algorithm)
7. [API Documentation](#api-documentation)
8. [Database Schema](#database-schema)
9. [System Components](#system-components)
10. [Configuration](#configuration)
11. [Contributing Guidelines](#contributing-guidelines)
12. [References](#references)

---

## Project Overview

### Purpose and Scope

The Dynamic Pricing System addresses a critical challenge in e-commerce: determining optimal product prices that maximize revenue while maintaining competitive positioning and inventory efficiency. This system implements data-driven pricing strategies that automatically adjust prices in response to:

- **Inventory fluctuations** (scarcity-based pricing)
- **Real-time demand signals** (order frequency analysis)
- **Temporal patterns** (peak hours and weekends)
- **Currency exchange rates** (multi-currency support)
- **Seller-defined rules** (custom pricing policies)

### Key Objectives

- Enable sellers to optimize revenue and inventory turnover
- Provide buyers with fair, market-driven pricing
- Maintain system transparency through comprehensive audit trails
- Support real-time analytics and performance monitoring
- Facilitate multi-currency transactions with automatic conversion

---

## Architectural Design

### System Architecture Overview

The system follows the **Model-View-Controller (MVC)** architectural pattern with layered separation of concerns:

```
┌─────────────────────────────────────────────────────┐
│                  Presentation Layer                  │
│          (Views, Public Interface, UI)               │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│              Controller Layer                        │
│   (Request Handling, Business Logic Orchestration)   │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│              Service Layer                           │
│   (PricingEngine, Analytics, Notifications)          │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│              Model Layer                             │
│      (Data Abstraction and Persistence)              │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│             Database Layer                           │
│            (MySQL/MariaDB Backend)                   │
└─────────────────────────────────────────────────────┘
```

### Core Design Patterns

1. **Service Layer Pattern**: Encapsulates business logic for pricing, analytics, and notifications
2. **Repository Pattern**: Abstracts data access through model classes
3. **Singleton Pattern**: Database connection management
4. **Observer Pattern**: Event-driven inventory and pricing updates
5. **Strategy Pattern**: Multiple pricing rule implementations

---

## Technical Stack

### Backend Technologies

| Component | Technology | Version |
|-----------|-----------|---------|
| **Server Language** | PHP | 7.4+ |
| **Framework** | Custom MVC | - |
| **Database** | MySQL/MariaDB | 5.7+ |
| **Package Manager** | Composer | 2.0+ |

### Frontend Technologies

| Component | Technology |
|-----------|-----------|
| **Markup** | HTML5 |
| **Styling** | CSS3 |
| **Scripting** | JavaScript (Vanilla) |
| **UI Components** | Custom Components |

### Key Libraries and Dependencies

- **PDO**: Database abstraction layer
- **cURL**: HTTP requests for exchange rate API integration
- **Session Management**: Custom session handling with security features
- **Logging Framework**: Custom file-based logging system

---

## Core Features

### 1. Dynamic Pricing Engine

The heart of the system, implementing sophisticated algorithms for price optimization:

- **Inventory-Based Pricing**: Scarcity premium for low stock, clearance discounts for high stock
- **Demand-Based Pricing**: Real-time adjustment based on 24-hour order history
- **Temporal Pricing**: Peak hours (12-2pm, 6-8pm) and weekend adjustments
- **Currency Management**: Real-time exchange rate integration
- **Custom Pricing Rules**: Seller-defined fixed, percentage, and range-based rules

### 2. Buyer Interface

Comprehensive shopping experience with:

- **Product Catalog**: Browse products by category with real-time pricing
- **Shopping Cart**: Persistent cart management with automatic price updates
- **Checkout Process**: Multi-step checkout with order confirmation
- **Order History**: Track past purchases with detailed order analytics
- **Price History**: View historical price trends for informed purchasing

### 3. Seller Interface

Complete inventory and pricing management:

- **Product Management**: Add, edit, and manage product listings
- **Pricing Dashboard**: Real-time pricing analytics and adjustment tools
- **Inventory Management**: Track stock levels with automatic alerts
- **Order Fulfillment**: Process and manage customer orders
- **Business Analytics**: Revenue, sales trends, and performance metrics
- **Seller Settings**: Account configuration and business profile

### 4. Analytics and Reporting

Data-driven insights for business intelligence:

- **Price History Tracking**: Complete audit trail of all price changes
- **Demand Analysis**: Order frequency and velocity metrics
- **Inventory Analytics**: Stock movement and turnover rates
- **Revenue Analytics**: Income trends and performance indicators
- **Comparative Analytics**: Performance benchmarking

### 5. Administrative Features

- **User Management**: Authentication and authorization
- **Audit Logging**: Comprehensive activity tracking
- **Notification System**: Real-time alerts for price changes and stock alerts
- **Email Integration**: Transaction confirmations and notifications

---

## Installation and Setup

### Prerequisites

- **PHP 7.4** or higher
- **MySQL 5.7** or higher (or MariaDB equivalent)
- **Apache Web Server** with mod_rewrite enabled
- **Composer** for dependency management
- **XAMPP** or similar local development environment

### Step 1: Environment Setup

```bash
# Clone or download the project
cd /path/to/htdocs/dynamic/dynamic_pricing

# Install dependencies
composer install

# Create logs directory
mkdir -p logs
chmod 755 logs
```

### Step 2: Database Configuration

```bash
# Import database schema
mysql -u root -p < dynamic_pricing_db.sql

# Or manually import in phpMyAdmin
```

### Step 3: Configuration Files

Create or edit configuration files in the `config/` directory:

**config/config.php**
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dynamic_pricing');
define('BASE_URL', 'http://localhost/dynamic/dynamic_pricing/');
?>
```

### Step 4: Environment Variables

Create `.env` file (if applicable):
```
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=dynamic_pricing
APP_DEBUG=true
```

### Step 5: Verify Installation

1. Navigate to `http://localhost/dynamic/dynamic_pricing/public/`
2. Test buyer and seller interfaces
3. Check log files: `logs/app.log`

---

## Pricing Algorithm

### Mathematical Foundation

The dynamic pricing engine implements a **multiplicative adjustment model**:

$$P_{final} = P_{current} \times (1 + A_{inventory}) \times (1 + A_{time}) \times (1 + A_{demand}) \times (1 + A_{rules})$$

Where:
- $P_{current}$ = Current product price
- $A_{inventory}$ = Inventory-based adjustment (-3% to +5%)
- $A_{time}$ = Temporal adjustment (-0% to +15%)
- $A_{demand}$ = Demand-based adjustment (-0% to +15%)
- $A_{rules}$ = Custom seller rules

### Component Algorithms

#### 1. Inventory-Based Adjustment

Implements scarcity pricing using a normalized quadratic function:

**For Low Stock** ($stock \leq lowThreshold$):
$$A_{inventory} = \left(1 - \left(\frac{stock}{lowThreshold}\right)^2\right) \times MAX\_INCREASE$$

**For High Stock** ($stock \geq highThreshold$):
$$A_{inventory} = -\min\left(\frac{stock - highThreshold}{highThreshold} \times MAX\_DECREASE, MAX\_DECREASE\right)$$

**For Normal Stock** ($lowThreshold < stock < highThreshold$):
$$A_{inventory} = \pm\frac{|stock - midpoint|}{|midpoint - boundary|} \times \frac{MAX}{2}$$

**Default Parameters**:
- $MAX\_INCREASE = 5\%$ (maximum scarcity premium)
- $MAX\_DECREASE = 3\%$ (maximum clearance discount)
- $MIN\_PROFIT\_MARGIN = 1\%$

#### 2. Temporal Adjustment

Implements time-based pricing based on hour of day and day of week:

```
Peak Hours (12-2pm, 6-8pm): +5%
Weekend (Saturday, Sunday): +10%
Regular Hours: 0%
```

#### 3. Demand-Based Adjustment

Analyzes order frequency within 24-hour rolling window:

```
Orders > 10: +15% (High demand)
Orders > 5:  +8%  (Medium demand)
Orders < 1:  0%   (Low demand - no penalty)
```

#### 4. Price Limit Enforcement

Final price undergoes boundary checking:

$$P_{limited} = \max(\min(P_{final}, P_{max}), P_{min})$$

Where:
- $P_{max} = P_{current} \times (1 + MAX\_INCREASE)$
- $P_{min} = P_{base} \times (1 + MIN\_PROFIT\_MARGIN)$

### Update Criteria

Price updates trigger when:
1. Stock falls below low threshold AND price would increase, OR
2. Stock exceeds high threshold AND price would decrease, OR
3. Calculated change exceeds 0.5% threshold

---

## API Documentation

### REST API Endpoints

#### Authentication Endpoints

```
POST   /api/v1/auth/register     - User registration
POST   /api/v1/auth/login        - User login
POST   /api/v1/auth/logout       - User logout
POST   /api/v1/auth/refresh      - Token refresh
```

#### Product Endpoints

```
GET    /api/v1/products          - List all products
GET    /api/v1/products/:id      - Get product details
POST   /api/v1/products          - Create new product (seller)
PUT    /api/v1/products/:id      - Update product (seller)
DELETE /api/v1/products/:id      - Delete product (seller)
```

#### Pricing Endpoints

```
GET    /api/v1/pricing/rules/:productId      - Get pricing rules
POST   /api/v1/pricing/rules                 - Create pricing rule
PUT    /api/v1/pricing/rules/:ruleId         - Update pricing rule
DELETE /api/v1/pricing/rules/:ruleId         - Delete pricing rule
GET    /api/v1/pricing/history/:productId    - Get price history
```

#### Cart & Orders

```
POST   /api/v1/cart/add          - Add to cart
POST   /api/v1/cart/remove       - Remove from cart
POST   /api/v1/checkout          - Create order
GET    /api/v1/orders/:id        - Get order details
GET    /api/v1/orders            - List user orders
```

#### Analytics Endpoints

```
GET    /api/v1/analytics/revenue      - Revenue analytics
GET    /api/v1/analytics/inventory    - Inventory analytics
GET    /api/v1/analytics/prices       - Price analysis
GET    /api/v1/analytics/demand       - Demand analysis
```

### Request/Response Format

**Standard Request**:
```json
{
  "action": "endpoint_action",
  "data": {
    "field1": "value1",
    "field2": "value2"
  }
}
```

**Standard Response**:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    "id": 1,
    "field1": "value1"
  },
  "timestamp": "2026-03-17T09:27:29Z"
}
```

---

## Database Schema

### Core Tables

#### Products Table
```sql
CREATE TABLE products (
  product_id INT PRIMARY KEY AUTO_INCREMENT,
  seller_id INT NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  sku VARCHAR(50) UNIQUE NOT NULL,
  product_description TEXT,
  current_price DECIMAL(10,2) NOT NULL,
  base_cost DECIMAL(10,2) NOT NULL,
  cost_currency VARCHAR(3) DEFAULT 'USD',
  price_currency VARCHAR(3) DEFAULT 'NGN',
  category VARCHAR(100),
  image_url VARCHAR(255),
  is_active BOOLEAN DEFAULT TRUE,
  last_price_update TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (seller_id) REFERENCES users(user_id),
  INDEX idx_seller (seller_id),
  INDEX idx_sku (sku),
  INDEX idx_category (category)
);
```

#### Inventory Table
```sql
CREATE TABLE inventory (
  inventory_id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT NOT NULL UNIQUE,
  quantity_available INT NOT NULL DEFAULT 0,
  quantity_reserved INT NOT NULL DEFAULT 0,
  low_stock_threshold INT DEFAULT 20,
  high_stock_threshold INT DEFAULT 100,
  reorder_point INT DEFAULT 10,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);
```

#### Pricing History Table
```sql
CREATE TABLE pricing_history (
  price_history_id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT NOT NULL,
  old_price DECIMAL(10,2) NOT NULL,
  new_price DECIMAL(10,2) NOT NULL,
  change_percentage DECIMAL(5,2),
  adjustment_reason VARCHAR(255),
  inventory_level INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
  INDEX idx_product (product_id),
  INDEX idx_created (created_at)
);
```

#### Pricing Rules Table
```sql
CREATE TABLE pricing_rules (
  rule_id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT NOT NULL,
  seller_id INT NOT NULL,
  rule_type ENUM('fixed', 'percentage', 'range') NOT NULL,
  min_value DECIMAL(10,2),
  max_value DECIMAL(10,2),
  percentage_change DECIMAL(5,2),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
  FOREIGN KEY (seller_id) REFERENCES users(user_id)
);
```

#### Orders Table
```sql
CREATE TABLE orders (
  order_id INT PRIMARY KEY AUTO_INCREMENT,
  buyer_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  payment_method VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (buyer_id) REFERENCES users(user_id),
  INDEX idx_buyer (buyer_id),
  INDEX idx_status (status),
  INDEX idx_created (created_at)
);
```

---

## System Components

### Core Classes and Modules

#### PricingEngine Service (`services/PricingEngine.php`)

**Primary Responsibility**: Real-time price calculation and optimization

**Key Methods**:
- `calculateOptimalPrice($productId)` - Main pricing algorithm
- `checkAndUpdatePrice($productId)` - Inventory-triggered price check
- `calculateInventoryAdjustment($stock, $low, $high)` - Scarcity pricing
- `calculateDemandAdjustment($productId)` - Demand-based adjustment
- `calculateTimeBasedAdjustment($sellerId)` - Temporal adjustment
- `enforcePriceLimits($price, $baseCost, $currentPrice)` - Boundary enforcement

#### AnalyticsService (`services/AnalyticsService.php`)

**Primary Responsibility**: Business intelligence and reporting

**Capabilities**:
- Revenue analysis by time period
- Inventory turnover metrics
- Demand forecasting
- Price elasticity analysis
- Seller performance metrics

#### NotificationService (`services/NotificationService.php`)

**Primary Responsibility**: Alert and notification management

**Features**:
- Price change notifications
- Low stock alerts
- Order status updates
- Email integration

### Controllers

- **PricingController**: Manage pricing rules and price updates
- **ProductController**: Product CRUD operations
- **AnalyticsController**: Generate and retrieve analytics
- **OrderController**: Process and track orders
- **SellerController**: Seller dashboard and management
- **BuyerController**: Buyer interface and shopping

### Models

- **Product**: Product data abstraction
- **Inventory**: Stock level management
- **PricingHistory**: Historical price tracking
- **PricingRule**: Custom pricing policies
- **Order**: Order management
- **User**: User accounts and authentication

---

## Configuration

### Environment Configuration

**config/config.php** - Main configuration file

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dynamic_pricing');

// Application Settings
define('BASE_URL', 'http://localhost/dynamic/dynamic_pricing/');
define('APP_DEBUG', true);
define('LOG_LEVEL', 'INFO');

// Pricing Engine Constants
define('MAX_PRICE_INCREASE', 0.05);     // 5%
define('MAX_PRICE_DECREASE', 0.03);     // 3%
define('MIN_PROFIT_MARGIN', 0.01);      // 1%

// Exchange Rate API
define('EXCHANGE_RATE_API', 'https://api.exchangerate-api.com/v4/latest/');

// Session Configuration
define('SESSION_TIMEOUT', 3600);        // 1 hour
?>
```

### Pricing Configuration

Adjust pricing algorithm parameters:

```php
// In PricingEngine class
const MAX_PRICE_INCREASE = 0.05;  // Increase to 10% for more aggressive pricing
const MAX_PRICE_DECREASE = 0.03;  // Decrease to 5% for faster inventory clearance
const MIN_PROFIT_MARGIN = 0.01;   // Adjust based on cost structure
```

---

## Contributing Guidelines

### Development Workflow

1. **Fork and Clone**: Create your development branch
2. **Code Standards**: Follow PSR-12 PHP standards
3. **Testing**: Write unit tests for new features
4. **Documentation**: Update README and code comments
5. **Commit**: Use descriptive commit messages
6. **Pull Request**: Submit PR with detailed description

### Code Style

```php
<?php
// Use meaningful variable names
$inventoryAdjustment = $this->calculateInventoryAdjustment(...);

// Include inline documentation
/**
 * Calculate optimal price based on all factors
 * @param int $productId Product identifier
 * @return float Calculated optimal price
 */
public function calculateOptimalPrice($productId) { }

// Proper error handling
try {
    // Risky operation
} catch (Exception $e) {
    Logger::error("Operation failed: " . $e->getMessage());
}
?>
```

### Testing

Run tests using:
```bash
php vendor/bin/phpunit
```

---

## Performance Considerations

### Optimization Strategies

1. **Database Indexing**: Indexes on `product_id`, `seller_id`, `created_at`
2. **Caching**: Cache exchange rates (30-minute TTL)
3. **Batch Processing**: Process price updates in scheduled batches
4. **Query Optimization**: Use efficient JOINs and WHERE clauses
5. **Lazy Loading**: Load inventory data only when needed

### Scalability

- Horizontal scaling through load balancing
- Database replication for read operations
- Asynchronous processing for batch operations
- API rate limiting and throttling

---

## Security Measures

### Implementation

1. **Authentication**: Session-based with secure cookies
2. **Authorization**: Role-based access control (RBAC)
3. **Input Validation**: Server-side validation for all inputs
4. **SQL Injection Prevention**: Prepared statements with parameterized queries
5. **CSRF Protection**: Token-based CSRF protection
6. **XSS Prevention**: Output encoding and Content Security Policy

---

## Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| Database connection fails | Verify credentials in config.php |
| Prices not updating | Check PricingEngine logs in logs/app.log |
| 404 errors | Ensure mod_rewrite is enabled |
| Permission denied errors | Set proper directory permissions (755) |

---

## References

### Academic Literature

1. Talluri, K. T., & Van Ryzin, G. J. (2004). *The Theory and Practice of Revenue Management*. Springer Science+Business Media.

2. Elmaghraby, W., & Keskinocak, P. (2003). "Dynamic Pricing in the Presence of Inventory Considerations: Research Overview, Current Practices, and Future Opportunities." *Management Science*, 49(10), 1287-1309.

3. Bitran, G. R., & Caldentey, R. (2003). "An Overview of Pricing Models for Revenue Management." *Manufacturing & Service Operations Management*, 5(2), 81-92.

### Technical Documentation

- [PHP Documentation](https://www.php.net/manual/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [REST API Best Practices](https://restfulapi.net/)
- [MVC Architecture Pattern](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)

---

## License

This project is licensed under the MIT License - see LICENSE file for details.

---

## Contact and Support

For questions, issues, or contributions, please contact the development team or submit issues through the project repository.

**Last Updated**: March 17, 2026
**Version**: 1.0.0
**Maintainers**: Development Team

---

*This README reflects best practices in e-commerce systems design, dynamic pricing theory, and software architecture. For academic inquiries or research applications, please refer to the referenced literature and contact the project maintainers.*
