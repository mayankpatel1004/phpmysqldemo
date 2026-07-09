<?php
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("Access denied. Run this script from command line only.\n");
}

echo "--- Starting Migration ---\n";

include './connection.php';
migrate($pdo);

function migrate(PDO $pdo): void
{
    // ---- Table: items ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `items` (
                `item_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `item_title` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `item_parent` INT NOT NULL DEFAULT 0,
                `item_type` VARCHAR(255) DEFAULT NULL,
                `item_sections_id` VARCHAR(255) DEFAULT NULL,
                `item_description` TEXT DEFAULT NULL,
                `attachment1` VARCHAR(255) DEFAULT NULL,
                `attachment2` VARCHAR(255) DEFAULT NULL,
                `item_shortdescription` TEXT DEFAULT NULL,
                `user_id` INT NOT NULL DEFAULT 0,
                `controller` VARCHAR(50) DEFAULT NULL,
                `action` VARCHAR(50) DEFAULT 'index',
                `published_at` DATE DEFAULT NULL,
                `published_end_at` DATE DEFAULT NULL,
                `meta_title` VARCHAR(255) DEFAULT NULL,
                `meta_description` TEXT DEFAULT NULL,
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'items' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'items' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'items': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: action ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `action` (
                `action_id` INT AUTO_INCREMENT PRIMARY KEY,
                `action` VARCHAR(255) DEFAULT NULL,
                `record_id` INT NOT NULL DEFAULT 0,
                `table_name` VARCHAR(255) DEFAULT NULL,
                `record_name` VARCHAR(255) DEFAULT NULL,
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'action' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'action' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'action': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: item_section ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `item_section` (
                `item_section_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `item_section_parent_id` INT NOT NULL DEFAULT 0,
                `section_title` VARCHAR(255) DEFAULT NULL,
                `section_alias` VARCHAR(255) DEFAULT NULL,
                `item_type` VARCHAR(255) DEFAULT NULL,
                `description` TEXT DEFAULT NULL,
                `attachment1` VARCHAR(255) DEFAULT NULL,
                `user_id` INTEGER DEFAULT 0,
                `meta_title` VARCHAR(255) DEFAULT NULL,
                `meta_description` TEXT DEFAULT NULL,
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'item_section' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'item_section' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'item_section': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: item_section_relation ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `item_section_relation` (
                `item_section_relation_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `item_id` BIGINT NOT NULL DEFAULT 0,
                `section_id` BIGINT NOT NULL DEFAULT 0,
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'item_section_relation' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'item_section_relation' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'item_section_relation': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: meta_details ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `meta_details` (
                `meta_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `parent_id` INT NOT NULL DEFAULT 0,
                `end_points` VARCHAR(255) DEFAULT NULL,
                `page_title` VARCHAR(255) DEFAULT NULL,
                `meta_title` VARCHAR(255) DEFAULT NULL,
                `meta_description` VARCHAR(255) DEFAULT NULL,
                `sidebar_title` VARCHAR(255) DEFAULT NULL,
                `sidebar_icon` VARCHAR(255) DEFAULT NULL,
                `sidebar_order` INT NOT NULL DEFAULT 0,
                `params` VARCHAR(255) DEFAULT NULL,
                `is_module` SMALLINT NOT NULL DEFAULT 0,
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'meta_details' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'meta_details' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'meta_details': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: role ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `role` (
                `role_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `role_title` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `item_type` VARCHAR(255) NOT NULL DEFAULT 'role',
                `display_on_listing` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `show_action_checkbox` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `allow_delete` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'role' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'role' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'role': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: role_access ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `role_access` (
                `role_access_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `role_id` INTEGER DEFAULT 0,
                `module_id` INTEGER DEFAULT 0,
                `grant_add` VARCHAR(1) NOT NULL DEFAULT 'N',
                `grant_edit` VARCHAR(1) NOT NULL DEFAULT 'N',
                `grant_delete` VARCHAR(1) NOT NULL DEFAULT 'N',
                `grant_view` VARCHAR(1) NOT NULL DEFAULT 'N',
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'role_access' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'role_access' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'role_access': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: site_config ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `site_config` (
                `config_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `config_title` VARCHAR(1024) DEFAULT NULL,
                `config_name` VARCHAR(1024) DEFAULT NULL,
                `config_value` TEXT DEFAULT NULL,
                `input_type` VARCHAR(15) DEFAULT NULL,
                `size` INTEGER NOT NULL DEFAULT 100,
                `maxlength` INTEGER NOT NULL DEFAULT 100,
                `input_type_title` VARCHAR(100) DEFAULT NULL,
                `classname` VARCHAR(100) DEFAULT 'textbox',
                `required` VARCHAR(1) DEFAULT 'O',
                `comments` VARCHAR(255) DEFAULT NULL,
                `additional` VARCHAR(100) DEFAULT NULL,
                `display_on_dashboard` VARCHAR(1) NOT NULL DEFAULT 'N',
                `display_on_third_party` VARCHAR(1) NOT NULL DEFAULT 'N',
                `site_config_parent_id` SMALLINT NOT NULL DEFAULT 0,
                `root_user_only` VARCHAR(1) NOT NULL DEFAULT 'N',
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'site_config' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'site_config' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'site_config': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: site_config_parent ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `site_config_parent` (
                `site_config_parent_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INTEGER NOT NULL,
                `site_config_title` VARCHAR(191) NOT NULL,
                `classname` VARCHAR(191) NOT NULL,
                `root_user_only` VARCHAR(1) NOT NULL,
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'site_config_parent' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'site_config_parent' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'site_config_parent': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: users ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `users` (
                `user_id` INT AUTO_INCREMENT PRIMARY KEY,
                `site_id` INT NOT NULL DEFAULT 0,
                `site_db` VARCHAR(255) DEFAULT NULL,
                `user_firstname` VARCHAR(255) DEFAULT NULL,
                `user_lastname` VARCHAR(255) DEFAULT NULL,
                `user_name` VARCHAR(255) DEFAULT NULL,
                `user_email` VARCHAR(255) DEFAULT NULL,
                `user_password` VARCHAR(255) DEFAULT NULL,
                `user_token` VARCHAR(255) DEFAULT NULL,
                `user_photo` VARCHAR(255) DEFAULT NULL,
                `user_role_id` SMALLINT NOT NULL DEFAULT 0,
                `is_developer_account` VARCHAR(1) NOT NULL DEFAULT 'N',
                `allow_delete` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `web_or_app` VARCHAR(4) NOT NULL DEFAULT 'App',
                `active_status` VARCHAR(25) NOT NULL DEFAULT 'N',
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'users' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'users' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'users': " . $e->getMessage() . "\n";
        }
    }

    // ---- Add column add_1 to users ----
    try {
        $sql = "ALTER TABLE `users` ADD COLUMN `add_1` VARCHAR(255) DEFAULT NULL";
        $pdo->exec($sql);
        echo "✅ Column 'add_1' added to 'users' successfully.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "ℹ️  Column 'add_1' already exists in 'users' – skipping.\n";
        } else {
            echo "❌ Failed to add column 'add_1' to 'users': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: customers ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `customers` (
                `customer_id` INT AUTO_INCREMENT PRIMARY KEY,
                `cart_id_pk` INTEGER DEFAULT 0,
                `cart_customer_id` VARCHAR(255) DEFAULT NULL,
                `customer_pin` INT NOT NULL DEFAULT 0,
                `pos_customer` VARCHAR(1) NOT NULL DEFAULT 'N',
                `name` VARCHAR(255) DEFAULT NULL,
                `first_name` VARCHAR(191) DEFAULT NULL,
                `last_name` VARCHAR(191) DEFAULT NULL,
                `email` VARCHAR(100) UNIQUE DEFAULT NULL,
                `password` VARCHAR(191) DEFAULT NULL,
                `birth_date` DATE DEFAULT NULL,
                `role_id` INT NOT NULL DEFAULT 0,
                `guest_customer` VARCHAR(1) NOT NULL DEFAULT '0',
                `access_token` VARCHAR(255) DEFAULT NULL,
                `security_question_id` INT NOT NULL DEFAULT 0,
                `security_answer` VARCHAR(191) DEFAULT NULL,
                `user_address1` VARCHAR(191) DEFAULT NULL,
                `user_address2` VARCHAR(191) DEFAULT NULL,
                `user_city` VARCHAR(191) DEFAULT NULL,
                `user_state` VARCHAR(191) DEFAULT NULL,
                `user_zipcode` VARCHAR(191) DEFAULT NULL,
                `user_country` VARCHAR(191) DEFAULT NULL,
                `contact_number` VARCHAR(191) DEFAULT NULL,
                `display_on_listing` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `show_action_checkbox` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `web_token` VARCHAR(255) DEFAULT NULL,
                `api_token` VARCHAR(255) DEFAULT NULL,
                `session_id` VARCHAR(255) DEFAULT NULL,
                `device_id` VARCHAR(255) DEFAULT NULL,
                `device_name` VARCHAR(255) DEFAULT NULL,
                `item_type` VARCHAR(25) NOT NULL DEFAULT 'users',
                `wallet_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `blocked` VARCHAR(1) NOT NULL DEFAULT 'N',
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `created_by_role` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'customers' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'customers' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'customers': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for customers
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_customers_role_id` ON `customers` (`role_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_customers_role_id' created on 'customers'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_customers_role_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_customers_role_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_customers_display_status` ON `customers` (`display_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_customers_display_status' created on 'customers'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_customers_display_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_customers_display_status': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_customers_created_at` ON `customers` (`created_at`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_customers_created_at' created on 'customers'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_customers_created_at' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_customers_created_at': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_cart ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_cart` (
                `cart_id` INT AUTO_INCREMENT PRIMARY KEY,
                `cart_customer_id` VARCHAR(255) DEFAULT NULL,
                `cart_sub_id` INT NOT NULL DEFAULT 0,
                `session_id` VARCHAR(255) DEFAULT NULL,
                `is_customer` VARCHAR(1) NOT NULL DEFAULT 'N',
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `birth_date` VARCHAR(255) DEFAULT NULL,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `billing_first_name` VARCHAR(255) DEFAULT NULL,
                `billing_last_name` VARCHAR(255) DEFAULT NULL,
                `billing_address_1` VARCHAR(255) DEFAULT NULL,
                `billing_address_2` VARCHAR(255) DEFAULT NULL,
                `billing_city` VARCHAR(255) DEFAULT NULL,
                `billing_state_id` INT NOT NULL DEFAULT 0,
                `billing_state` VARCHAR(255) DEFAULT NULL,
                `billing_country_id` INT NOT NULL DEFAULT 0,
                `billing_country` VARCHAR(255) DEFAULT NULL,
                `billing_zipcode` VARCHAR(255) DEFAULT NULL,
                `billing_contact` VARCHAR(255) DEFAULT NULL,
                `billing_email` VARCHAR(255) DEFAULT NULL,
                `shipping_first_name` VARCHAR(255) DEFAULT NULL,
                `shipping_last_name` VARCHAR(255) DEFAULT NULL,
                `shipping_address_1` VARCHAR(255) DEFAULT NULL,
                `shipping_address_2` VARCHAR(255) DEFAULT NULL,
                `shipping_city` VARCHAR(255) DEFAULT NULL,
                `shipping_state_id` INT NOT NULL DEFAULT 0,
                `shipping_state` VARCHAR(255) DEFAULT NULL,
                `shipping_country` VARCHAR(255) DEFAULT NULL,
                `shipping_country_id` INT NOT NULL DEFAULT 0,
                `shipping_zipcode` VARCHAR(255) DEFAULT NULL,
                `shipping_contact` VARCHAR(255) DEFAULT NULL,
                `shipping_email` VARCHAR(255) DEFAULT NULL,
                `coupon_code` VARCHAR(255) DEFAULT NULL,
                `coupon_type` VARCHAR(255) DEFAULT NULL,
                `item_coupon_type` VARCHAR(255) DEFAULT NULL,
                `currancy` VARCHAR(10) DEFAULT NULL,
                `cashback_amount_applied` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `coupon_amount_applied` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `cashback_wallet_amount_used` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_ordered_quantity` INT NOT NULL DEFAULT 0,
                `total_items_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_items_tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_items_shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `order_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `ip_address` VARCHAR(45) DEFAULT NULL,
                `is_pos_order` VARCHAR(1) NOT NULL DEFAULT 'N',
                `payment_type` VARCHAR(255) DEFAULT NULL,
                `shipping_type` VARCHAR(255) DEFAULT NULL,
                `shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `order_notes` TEXT DEFAULT NULL,
                `device` VARCHAR(255) DEFAULT '1.0',
                `device_id` VARCHAR(255) DEFAULT NULL,
                `device_type` VARCHAR(255) DEFAULT NULL,
                `browser_name` VARCHAR(255) DEFAULT NULL,
                `user_agent` TEXT DEFAULT NULL,
                `browser_version` VARCHAR(100) DEFAULT NULL,
                `platform` VARCHAR(255) DEFAULT NULL,
                `browser_pattern` VARCHAR(255) DEFAULT NULL,
                `site_id` SMALLINT NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `expire_at` TIMESTAMP DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_cart' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_cart' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_cart': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_cart
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_cart_customer_id` ON `ec_cart` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_cart_customer_id' created on 'ec_cart'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_cart_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_cart_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_cart_session_id` ON `ec_cart` (`session_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_cart_session_id' created on 'ec_cart'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_cart_session_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_cart_session_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_cart_created_at` ON `ec_cart` (`created_at`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_cart_created_at' created on 'ec_cart'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_cart_created_at' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_cart_created_at': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_cart_product ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_cart_product` (
                `cart_product_id` INT AUTO_INCREMENT PRIMARY KEY,
                `cart_id` INT NOT NULL DEFAULT 0,
                `cart_sub_id` INT NOT NULL DEFAULT 0,
                `customer_id` INT NOT NULL DEFAULT 0,
                `item_id` INT NOT NULL DEFAULT 0,
                `user_id` INT NOT NULL DEFAULT 0,
                `user_name` VARCHAR(255) DEFAULT NULL,
                `user_email` VARCHAR(255) DEFAULT NULL,
                `product_price_id` INT NOT NULL DEFAULT 0,
                `item_name` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `item_code` VARCHAR(255) DEFAULT NULL,
                `ordered_quantity` INT NOT NULL DEFAULT 0,
                `product_attribute_1` VARCHAR(255) DEFAULT NULL,
                `product_attribute_2` VARCHAR(255) DEFAULT NULL,
                `product_attribute_3` VARCHAR(255) DEFAULT NULL,
                `product_option_1` VARCHAR(255) DEFAULT NULL,
                `product_option_2` VARCHAR(255) DEFAULT NULL,
                `product_option_3` VARCHAR(255) DEFAULT NULL,
                `product_option_4` VARCHAR(255) DEFAULT NULL,
                `product_option_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `product_option_price_display` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `currency` VARCHAR(255) DEFAULT NULL,
                `is_taxable` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `item_tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `is_default_price` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `product_quantity` INT NOT NULL DEFAULT 0,
                `special_price_from_date` DATE DEFAULT NULL,
                `special_price_to_date` DATE DEFAULT NULL,
                `item_price_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_tax_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `final_item_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_cart_product' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_cart_product' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_cart_product': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_cart_product
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_cart_product_cart_id` ON `ec_cart_product` (`cart_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_cart_product_cart_id' created on 'ec_cart_product'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_cart_product_cart_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_cart_product_cart_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_cart_product_item_id` ON `ec_cart_product` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_cart_product_item_id' created on 'ec_cart_product'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_cart_product_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_cart_product_item_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_cart_product_customer_id` ON `ec_cart_product` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_cart_product_customer_id' created on 'ec_cart_product'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_cart_product_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_cart_product_customer_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_cashback_credit_transaction ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_cashback_credit_transaction` (
                `cashbackcredit_transaction_id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL DEFAULT 0,
                `order_id` INT DEFAULT 0,
                `customer_id` INT NOT NULL DEFAULT 0,
                `cashback_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `site_id` SMALLINT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_cashback_credit_transaction' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_cashback_credit_transaction' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_cashback_credit_transaction': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_cashback_credit_transaction
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_cashback_credit_customer_id` ON `ec_cashback_credit_transaction` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_cashback_credit_customer_id' created on 'ec_cashback_credit_transaction'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_cashback_credit_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_cashback_credit_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_cashback_credit_order_id` ON `ec_cashback_credit_transaction` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_cashback_credit_order_id' created on 'ec_cashback_credit_transaction'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_cashback_credit_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_cashback_credit_order_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_cashback_transaction ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_cashback_transaction` (
                `cashback_id` INT AUTO_INCREMENT PRIMARY KEY,
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `customer_email` VARCHAR(255) DEFAULT NULL,
                `order_id` INT NOT NULL DEFAULT 0,
                `currency` VARCHAR(10) DEFAULT NULL,
                `cashback_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `transaction_id` VARCHAR(255) DEFAULT NULL,
                `coupon_code` VARCHAR(255) DEFAULT NULL,
                `coupon_type` VARCHAR(255) DEFAULT NULL,
                `item_coupon_type` VARCHAR(255) DEFAULT NULL,
                `site_id` SMALLINT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_cashback_transaction' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_cashback_transaction' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_cashback_transaction': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_cashback_transaction
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_cashback_customer_id` ON `ec_cashback_transaction` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_cashback_customer_id' created on 'ec_cashback_transaction'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_cashback_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_cashback_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_cashback_order_id` ON `ec_cashback_transaction` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_cashback_order_id' created on 'ec_cashback_transaction'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_cashback_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_cashback_order_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_closeday ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_closeday` (
                `closeday_id` INT AUTO_INCREMENT PRIMARY KEY,
                `opening_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `closing_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `closing_time` TIMESTAMP DEFAULT NULL,
                `closed_by` INT DEFAULT NULL,
                `closed_by_name` VARCHAR(255) DEFAULT NULL,
                `closing_notes` TEXT DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_closeday' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_closeday' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_closeday': " . $e->getMessage() . "\n";
        }
    }

    // Index for ec_closeday
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_closeday_closed_by` ON `ec_closeday` (`closed_by`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_closeday_closed_by' created on 'ec_closeday'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_closeday_closed_by' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_closeday_closed_by': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_coupon ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_coupon` (
                `coupon_id` INT AUTO_INCREMENT PRIMARY KEY,
                `item_type` VARCHAR(255) DEFAULT 'discount',
                `coupon_code_type` VARCHAR(255) NOT NULL DEFAULT 'alluser',
                `specific_user_id` INT NOT NULL DEFAULT 0,
                `coupon_title` VARCHAR(255) DEFAULT NULL,
                `coupon_alias` VARCHAR(255) DEFAULT NULL,
                `coupon_description` TEXT DEFAULT NULL,
                `coupon_terms_conditions` TEXT DEFAULT NULL,
                `order_calculation_required` VARCHAR(1) NOT NULL DEFAULT 'N',
                `minimum_order_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `coupon_item_type` VARCHAR(255) DEFAULT 'discount',
                `coupon_item_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `coupon_code` VARCHAR(255) UNIQUE DEFAULT NULL,
                `amount_type` VARCHAR(255) DEFAULT 'Fixed',
                `currency` VARCHAR(20) DEFAULT '$',
                `coupon_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `coupon_amount_maximum` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `user_per_customer` INT DEFAULT 1,
                `created_user_id` INT NOT NULL DEFAULT 0,
                `start_date` TIMESTAMP DEFAULT NULL,
                `end_date` TIMESTAMP DEFAULT NULL,
                `site_id` SMALLINT DEFAULT NULL,
                `item_type_alias` VARCHAR(255) DEFAULT NULL,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_user_id` INT DEFAULT NULL,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_coupon' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_coupon' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_coupon': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_coupon
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_coupon_display_status` ON `ec_coupon` (`display_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_coupon_display_status' created on 'ec_coupon'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_coupon_display_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_coupon_display_status': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_coupon_start_date` ON `ec_coupon` (`start_date`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_coupon_start_date' created on 'ec_coupon'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_coupon_start_date' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_coupon_start_date': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_coupon_end_date` ON `ec_coupon` (`end_date`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_coupon_end_date' created on 'ec_coupon'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_coupon_end_date' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_coupon_end_date': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_order ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_order` (
                `order_id` INT AUTO_INCREMENT PRIMARY KEY,
                `order_id_unique` VARCHAR(255) UNIQUE DEFAULT NULL,
                `cart_id` INT NOT NULL DEFAULT 0,
                `cart_customer_id` VARCHAR(255) DEFAULT NULL,
                `cart_sub_id` INT NOT NULL DEFAULT 0,
                `session_id` VARCHAR(255) DEFAULT NULL,
                `is_customer` VARCHAR(1) NOT NULL DEFAULT 'N',
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `cancelled_by` INT DEFAULT NULL,
                `cancelled_by_name` VARCHAR(255) DEFAULT NULL,
                `cancelled_reason` VARCHAR(255) DEFAULT NULL,
                `cancelled_at` TIMESTAMP DEFAULT NULL,
                `billing_first_name` VARCHAR(255) DEFAULT NULL,
                `billing_last_name` VARCHAR(255) DEFAULT NULL,
                `billing_address_1` VARCHAR(255) DEFAULT NULL,
                `billing_address_2` VARCHAR(255) DEFAULT NULL,
                `billing_city` VARCHAR(255) DEFAULT NULL,
                `billing_state_id` INT NOT NULL DEFAULT 0,
                `billing_state` VARCHAR(255) DEFAULT NULL,
                `billing_country_id` INT NOT NULL DEFAULT 0,
                `billing_country` VARCHAR(255) DEFAULT NULL,
                `billing_zipcode` VARCHAR(255) DEFAULT NULL,
                `billing_contact` VARCHAR(255) DEFAULT NULL,
                `billing_email` VARCHAR(255) DEFAULT NULL,
                `shipping_first_name` VARCHAR(255) DEFAULT NULL,
                `shipping_last_name` VARCHAR(255) DEFAULT NULL,
                `shipping_address_1` VARCHAR(255) DEFAULT NULL,
                `shipping_address_2` VARCHAR(255) DEFAULT NULL,
                `shipping_city` VARCHAR(255) DEFAULT NULL,
                `shipping_state_id` INT NOT NULL DEFAULT 0,
                `shipping_state` VARCHAR(255) DEFAULT NULL,
                `shipping_country` VARCHAR(255) DEFAULT NULL,
                `shipping_country_id` INT NOT NULL DEFAULT 0,
                `shipping_zipcode` VARCHAR(255) DEFAULT NULL,
                `shipping_contact` VARCHAR(255) DEFAULT NULL,
                `shipping_email` VARCHAR(255) DEFAULT NULL,
                `coupon_code` VARCHAR(255) DEFAULT NULL,
                `coupon_type` VARCHAR(255) DEFAULT NULL,
                `item_coupon_type` VARCHAR(255) DEFAULT NULL,
                `currency` VARCHAR(10) DEFAULT NULL,
                `cashback_amount_applied` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `coupon_amount_applied` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `cashback_wallet_amount_used` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_ordered_quantity` INT NOT NULL DEFAULT 0,
                `total_items_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_items_tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_items_shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `order_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `ip_address` VARCHAR(45) DEFAULT NULL,
                `is_pos_order` VARCHAR(1) NOT NULL DEFAULT 'N',
                `payment_type` VARCHAR(255) DEFAULT NULL,
                `shipping_type` VARCHAR(255) DEFAULT NULL,
                `shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `order_notes` TEXT DEFAULT NULL,
                `device` VARCHAR(255) DEFAULT '1.0',
                `device_id` VARCHAR(255) DEFAULT NULL,
                `device_type` VARCHAR(255) DEFAULT NULL,
                `browser_name` VARCHAR(255) DEFAULT NULL,
                `user_agent` TEXT DEFAULT NULL,
                `browser_version` VARCHAR(100) DEFAULT NULL,
                `platform` VARCHAR(255) DEFAULT NULL,
                `browser_pattern` VARCHAR(255) DEFAULT NULL,
                `site_id` SMALLINT NOT NULL DEFAULT 0,
                `sync_to_live` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `deleted_by` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `expire_at` TIMESTAMP DEFAULT NULL,
                `transaction_id` VARCHAR(255) DEFAULT NULL,
                `payment_status` VARCHAR(255) DEFAULT NULL,
                `cashback_credited` VARCHAR(1) NOT NULL DEFAULT 'N',
                `cashback_credited_user_id` INT NOT NULL DEFAULT 0,
                `cashback_credited_date` TIMESTAMP DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_order' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_order' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_order': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_order
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_order_customer_id` ON `ec_order` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_order_customer_id' created on 'ec_order'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_order_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_order_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_order_order_status` ON `ec_order` (`order_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_order_order_status' created on 'ec_order'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_order_order_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_order_order_status': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_order_created_at` ON `ec_order` (`created_at`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_order_created_at' created on 'ec_order'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_order_created_at' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_order_created_at': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_order_payment_status` ON `ec_order` (`payment_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_order_payment_status' created on 'ec_order'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_order_payment_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_order_payment_status': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_order_payment_details ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_order_payment_details` (
                `order_payment_details_id` INT AUTO_INCREMENT PRIMARY KEY,
                `order_id` INT NOT NULL DEFAULT 0,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `order_key` VARCHAR(255) DEFAULT NULL,
                `order_value` TEXT DEFAULT NULL,
                `payment_gateway_name` VARCHAR(255) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_order_payment_details' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_order_payment_details' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_order_payment_details': " . $e->getMessage() . "\n";
        }
    }

    // Index for ec_order_payment_details
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_payment_details_order_id` ON `ec_order_payment_details` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_payment_details_order_id' created on 'ec_order_payment_details'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_payment_details_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_payment_details_order_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_order_products ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_order_products` (
                `order_product_id` INT AUTO_INCREMENT PRIMARY KEY,
                `cart_id` INT NOT NULL DEFAULT 0,
                `order_id` INT NOT NULL DEFAULT 0,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `cart_sub_id` INT NOT NULL DEFAULT 0,
                `customer_id` INT NOT NULL DEFAULT 0,
                `item_id` INT NOT NULL DEFAULT 0,
                `user_id` INT NOT NULL DEFAULT 0,
                `user_name` VARCHAR(255) DEFAULT NULL,
                `user_email` VARCHAR(255) DEFAULT NULL,
                `product_price_id` INT NOT NULL DEFAULT 0,
                `item_name` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `item_code` VARCHAR(255) DEFAULT NULL,
                `ordered_quantity` INT NOT NULL DEFAULT 0,
                `product_attribute_1` VARCHAR(255) DEFAULT NULL,
                `product_attribute_2` VARCHAR(255) DEFAULT NULL,
                `product_attribute_3` VARCHAR(255) DEFAULT NULL,
                `product_option_1` VARCHAR(255) DEFAULT NULL,
                `product_option_2` VARCHAR(255) DEFAULT NULL,
                `product_option_3` VARCHAR(255) DEFAULT NULL,
                `product_option_4` VARCHAR(255) DEFAULT NULL,
                `product_option_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `product_option_price_display` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `currency` VARCHAR(255) DEFAULT NULL,
                `is_taxable` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `item_tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `is_default_price` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `item_price_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_tax_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `final_item_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `review_added` VARCHAR(1) NOT NULL DEFAULT 'N',
                `review_request_counter` SMALLINT NOT NULL DEFAULT 0,
                `request_exchange_refund` VARCHAR(1) NOT NULL DEFAULT 'N',
                `exchange_refund_type` VARCHAR(255) DEFAULT NULL,
                `request_exchange_refund_approved` VARCHAR(1) NOT NULL DEFAULT 'N',
                `request_exchange_refund_approved_date` TIMESTAMP DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_order_products' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_order_products' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_order_products': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_order_products
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_order_products_order_id` ON `ec_order_products` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_order_products_order_id' created on 'ec_order_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_order_products_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_order_products_order_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_order_products_item_id` ON `ec_order_products` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_order_products_item_id' created on 'ec_order_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_order_products_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_order_products_item_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_order_products_customer_id` ON `ec_order_products` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_order_products_customer_id' created on 'ec_order_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_order_products_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_order_products_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_order_products_product_price_id` ON `ec_order_products` (`product_price_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_order_products_product_price_id' created on 'ec_order_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_order_products_product_price_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_order_products_product_price_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_order_products_return ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_order_products_return` (
                `products_return_id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL DEFAULT 0,
                `user_name` VARCHAR(255) DEFAULT NULL,
                `user_email` VARCHAR(255) DEFAULT NULL,
                `product_price_id` INT NOT NULL DEFAULT 0,
                `item_id` INT NOT NULL DEFAULT 0,
                `item_title` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `item_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_quantity` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_tax` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_final_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `order_id` INT NOT NULL DEFAULT 0,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `order_product_id` INT NOT NULL DEFAULT 0,
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `customer_email` VARCHAR(255) DEFAULT NULL,
                `customer_contact` VARCHAR(255) DEFAULT NULL,
                `exchange_refund` VARCHAR(255) DEFAULT NULL,
                `exchange_refund_reason` VARCHAR(255) DEFAULT NULL,
                `exchange_refund_description` TEXT DEFAULT NULL,
                `admin_approved` VARCHAR(1) NOT NULL DEFAULT 'N',
                `admin_notes` TEXT DEFAULT NULL,
                `admin_approve_date` TIMESTAMP DEFAULT NULL,
                `photo_1` VARCHAR(255) DEFAULT NULL,
                `photo_2` VARCHAR(255) DEFAULT NULL,
                `photo_3` VARCHAR(255) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_order_products_return' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_order_products_return' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_order_products_return': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_order_products_return
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_products_return_order_id` ON `ec_order_products_return` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_products_return_order_id' created on 'ec_order_products_return'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_products_return_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_products_return_order_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_products_return_customer_id` ON `ec_order_products_return` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_products_return_customer_id' created on 'ec_order_products_return'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_products_return_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_products_return_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_products_return_item_id` ON `ec_order_products_return` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_products_return_item_id' created on 'ec_order_products_return'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_products_return_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_products_return_item_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_order_products_return_logs ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_order_products_return_logs` (
                `products_return_id` INT AUTO_INCREMENT PRIMARY KEY,
                `exchange_refund_primary_key` INT NOT NULL DEFAULT 0,
                `created_by` INT NOT NULL DEFAULT 0,
                `created_by_name` VARCHAR(255) DEFAULT NULL,
                `user_id` INT NOT NULL DEFAULT 0,
                `user_name` VARCHAR(255) DEFAULT NULL,
                `user_email` VARCHAR(255) DEFAULT NULL,
                `product_price_id` INT NOT NULL DEFAULT 0,
                `item_id` INT NOT NULL DEFAULT 0,
                `item_title` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `item_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_quantity` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_tax` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_final_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `order_id` INT NOT NULL DEFAULT 0,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `order_product_id` INT NOT NULL DEFAULT 0,
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `customer_email` VARCHAR(255) DEFAULT NULL,
                `customer_contact` VARCHAR(255) DEFAULT NULL,
                `exchange_refund` VARCHAR(255) DEFAULT NULL,
                `exchange_refund_reason` VARCHAR(255) DEFAULT NULL,
                `exchange_refund_description` TEXT DEFAULT NULL,
                `admin_approved` VARCHAR(1) NOT NULL DEFAULT 'N',
                `admin_notes` TEXT DEFAULT NULL,
                `admin_approve_date` TIMESTAMP DEFAULT NULL,
                `photo_1` VARCHAR(255) DEFAULT NULL,
                `photo_2` VARCHAR(255) DEFAULT NULL,
                `photo_3` VARCHAR(255) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_order_products_return_logs' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_order_products_return_logs' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_order_products_return_logs': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_order_products_return_logs
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_return_logs_order_id` ON `ec_order_products_return_logs` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_return_logs_order_id' created on 'ec_order_products_return_logs'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_return_logs_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_return_logs_order_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_return_logs_customer_id` ON `ec_order_products_return_logs` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_return_logs_customer_id' created on 'ec_order_products_return_logs'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_return_logs_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_return_logs_customer_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_order_status ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_order_status` (
                `order_status_id` INT AUTO_INCREMENT PRIMARY KEY,
                `order_id` INT NOT NULL,
                `order_status` VARCHAR(255) DEFAULT NULL,
                `updated_status` VARCHAR(255) DEFAULT NULL,
                `updated_by` INT NOT NULL DEFAULT 0,
                `updated_by_name` VARCHAR(255) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_order_status' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_order_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_order_status': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_order_status
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_order_status_order_id` ON `ec_order_status` (`order_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_order_status_order_id' created on 'ec_order_status'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_order_status_order_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_order_status_order_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_order_status_updated_status` ON `ec_order_status` (`updated_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_order_status_updated_status' created on 'ec_order_status'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_order_status_updated_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_order_status_updated_status': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_products ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_products` (
                `item_id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL DEFAULT 0,
                `pos_user` VARCHAR(1) NOT NULL DEFAULT 'N',
                `item_title` VARCHAR(255) DEFAULT NULL,
                `item_alias` VARCHAR(255) UNIQUE DEFAULT NULL,
                `item_code` VARCHAR(255) DEFAULT NULL,
                `parent_item_id` INT NOT NULL DEFAULT 0,
                `item_type_alias` VARCHAR(255) DEFAULT NULL,
                `item_section_alias` VARCHAR(255) DEFAULT NULL,
                `item_category_alias` VARCHAR(255) DEFAULT NULL,
                `item_tags_alias` VARCHAR(255) DEFAULT NULL,
                `item_format_alias` VARCHAR(255) DEFAULT NULL,
                `item_template_name` VARCHAR(255) DEFAULT NULL,
                `item_weight` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `is_download_product` VARCHAR(1) NOT NULL DEFAULT 'N',
                `is_free_product` VARCHAR(1) NOT NULL DEFAULT 'N',
                `in_stock` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `currency` VARCHAR(20) DEFAULT NULL,
                `item_shipping_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `comment_count` INT NOT NULL DEFAULT 0,
                `item_description` TEXT DEFAULT NULL,
                `item_short_description` VARCHAR(1024) DEFAULT NULL,
                `item_terms_conditions` VARCHAR(1024) DEFAULT NULL,
                `meta_title` VARCHAR(255) DEFAULT NULL,
                `meta_description` TEXT DEFAULT NULL,
                `publish_date` DATE DEFAULT NULL,
                `publish_end_date` DATE DEFAULT NULL,
                `user_name` VARCHAR(255) DEFAULT NULL,
                `user_email` VARCHAR(255) DEFAULT NULL,
                `file1` VARCHAR(255) DEFAULT NULL,
                `file2` VARCHAR(255) DEFAULT NULL,
                `file3` VARCHAR(255) DEFAULT NULL,
                `total_visit` INT DEFAULT 0,
                `internal_link` VARCHAR(255) DEFAULT NULL,
                `external_link` VARCHAR(255) DEFAULT NULL,
                `is_featured` VARCHAR(1) NOT NULL DEFAULT 'N',
                `is_home` VARCHAR(1) NOT NULL DEFAULT 'N',
                `is_sidebar` VARCHAR(1) NOT NULL DEFAULT 'N',
                `is_returnable` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `top_ranked` VARCHAR(1) NOT NULL DEFAULT 'N',
                `highest_rated` VARCHAR(1) NOT NULL DEFAULT 'N',
                `most_viewed` VARCHAR(1) NOT NULL DEFAULT 'N',
                `related_products` VARCHAR(255) DEFAULT NULL,
                `total_sold` INT NOT NULL DEFAULT 0,
                `is_taxable` VARCHAR(1) NOT NULL DEFAULT 'N',
                `pos_online` SMALLINT NOT NULL DEFAULT 0,
                `item_css_class` VARCHAR(255) DEFAULT NULL,
                `display_on_sitemap` VARCHAR(1) NOT NULL DEFAULT 'N',
                `display_order` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `site_id` INT NOT NULL DEFAULT 0,
                `deleted_user_id` INT NOT NULL DEFAULT 0,
                `deleted_by_name` VARCHAR(255) DEFAULT NULL,
                `deleted_time` VARCHAR(255) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `total_reviews` INT NOT NULL DEFAULT 0,
                `avg_ratings` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `has_options` VARCHAR(1) NOT NULL DEFAULT 'N',
                `total_visitors` INT NOT NULL DEFAULT 0
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_products' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_products' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_products': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_products
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_products_user_id` ON `ec_products` (`user_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_products_user_id' created on 'ec_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_products_user_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_products_user_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_products_display_status` ON `ec_products` (`display_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_products_display_status' created on 'ec_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_products_display_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_products_display_status': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_products_item_type_alias` ON `ec_products` (`item_type_alias`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_products_item_type_alias' created on 'ec_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_products_item_type_alias' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_products_item_type_alias': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_ec_products_item_code` ON `ec_products` (`item_code`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_ec_products_item_code' created on 'ec_products'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_ec_products_item_code' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_ec_products_item_code': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_product_price ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_product_price` (
                `product_price_id` INT AUTO_INCREMENT PRIMARY KEY,
                `item_id` INT NOT NULL DEFAULT 0,
                `user_id` INT NOT NULL DEFAULT 0,
                `product_attribute_1` VARCHAR(255) DEFAULT NULL,
                `product_attribute_2` VARCHAR(255) DEFAULT NULL,
                `product_attribute_3` VARCHAR(255) DEFAULT NULL,
                `product_option_1` VARCHAR(255) DEFAULT NULL,
                `product_option_2` VARCHAR(255) DEFAULT NULL,
                `product_option_3` VARCHAR(255) DEFAULT NULL,
                `product_option_4` INT NOT NULL DEFAULT 0,
                `product_option_price` DECIMAL(10,2) DEFAULT NULL,
                `product_option_price_display` DECIMAL(10,2) DEFAULT NULL,
                `currency` VARCHAR(255) DEFAULT NULL,
                `item_tax_amount` DECIMAL(10,2) DEFAULT 0.00,
                `min_quantity_notification` SMALLINT NOT NULL DEFAULT 0,
                `item_shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `is_default_price` VARCHAR(1) NOT NULL DEFAULT 'N',
                `product_quantity` INT NOT NULL DEFAULT 0,
                `special_price_from_date` DATE DEFAULT NULL,
                `special_price_to_date` DATE DEFAULT NULL,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `site_id` SMALLINT NOT NULL DEFAULT 0
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_product_price' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_product_price' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_product_price': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_product_price
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_product_price_item_id` ON `ec_product_price` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_product_price_item_id' created on 'ec_product_price'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_product_price_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_product_price_item_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_product_price_display_status` ON `ec_product_price` (`display_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_product_price_display_status' created on 'ec_product_price'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_product_price_display_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_product_price_display_status': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_product_price_log ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_product_price_log` (
                `product_price_log_id` INT AUTO_INCREMENT PRIMARY KEY,
                `product_price_id` INT NOT NULL DEFAULT 0,
                `item_id` INT NOT NULL DEFAULT 0,
                `item_title` VARCHAR(255) DEFAULT NULL,
                `user_id` INT NOT NULL DEFAULT 0,
                `product_option_price` DECIMAL(10,2) DEFAULT NULL,
                `product_option_price_old` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `product_option_price_display` DECIMAL(10,2) DEFAULT NULL,
                `product_option_price_display_old` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_tax_amount` DECIMAL(10,2) DEFAULT 0.00,
                `item_tax_amount_old` INT NOT NULL DEFAULT 0,
                `item_shipping_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `item_shipping_amount_old` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `is_default_price` VARCHAR(1) NOT NULL DEFAULT 'N',
                `product_quantity` INT NOT NULL DEFAULT 0,
                `product_quantity_old` INT NOT NULL DEFAULT 0,
                `login_id` INT NOT NULL DEFAULT 0,
                `login_name` VARCHAR(255) DEFAULT NULL,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `created_at` TIMESTAMP DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_product_price_log' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_product_price_log' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_product_price_log': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_product_price_log
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_price_log_product_price_id` ON `ec_product_price_log` (`product_price_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_price_log_product_price_id' created on 'ec_product_price_log'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_price_log_product_price_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_price_log_product_price_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_price_log_item_id` ON `ec_product_price_log` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_price_log_item_id' created on 'ec_product_price_log'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_price_log_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_price_log_item_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_product_reviews ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_product_reviews` (
                `product_review_id` INT AUTO_INCREMENT PRIMARY KEY,
                `item_id` INT NOT NULL DEFAULT 0,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `customer_email` VARCHAR(255) DEFAULT NULL,
                `customer_phone` VARCHAR(255) DEFAULT NULL,
                `ratings` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `review_text` TEXT DEFAULT NULL,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'P',
                `site_id` SMALLINT NOT NULL DEFAULT 0,
                `deleted_date` DATE DEFAULT NULL,
                `deleted_user_id` INT NOT NULL DEFAULT 0,
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_product_reviews' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_product_reviews' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_product_reviews': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_product_reviews
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_product_reviews_item_id` ON `ec_product_reviews` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_product_reviews_item_id' created on 'ec_product_reviews'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_product_reviews_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_product_reviews_item_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_product_reviews_customer_id` ON `ec_product_reviews` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_product_reviews_customer_id' created on 'ec_product_reviews'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_product_reviews_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_product_reviews_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_product_reviews_display_status` ON `ec_product_reviews` (`display_status`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_product_reviews_display_status' created on 'ec_product_reviews'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_product_reviews_display_status' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_product_reviews_display_status': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_product_specifications ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_product_specifications` (
                `product_specification_id` INT AUTO_INCREMENT PRIMARY KEY,
                `item_id` INT NOT NULL DEFAULT 0,
                `item_alias` VARCHAR(255) DEFAULT NULL,
                `specification_title` VARCHAR(255) DEFAULT NULL,
                `specification_value` VARCHAR(255) DEFAULT NULL,
                `specification_type` VARCHAR(255) DEFAULT NULL,
                `site_id` SMALLINT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_product_specifications' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_product_specifications' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_product_specifications': " . $e->getMessage() . "\n";
        }
    }

    // Index for ec_product_specifications
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_specifications_item_id` ON `ec_product_specifications` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_specifications_item_id' created on 'ec_product_specifications'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_specifications_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_specifications_item_id': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_search_terms ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_search_terms` (
                `search_terms_id` INT AUTO_INCREMENT PRIMARY KEY,
                `keyword` VARCHAR(255) UNIQUE DEFAULT NULL,
                `search_count` INT NOT NULL DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_search_terms' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_search_terms' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_search_terms': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_search_terms_logs ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_search_terms_logs` (
                `search_terms_id` INT AUTO_INCREMENT PRIMARY KEY,
                `keyword` VARCHAR(255) DEFAULT NULL,
                `search_count` INT NOT NULL DEFAULT 0,
                `is_web` VARCHAR(1) NOT NULL DEFAULT 'N',
                `ip_address` VARCHAR(45) DEFAULT NULL,
                `customer_id` INT NOT NULL DEFAULT 0,
                `customer_name` VARCHAR(255) DEFAULT NULL,
                `customer_email` VARCHAR(255) DEFAULT NULL,
                `browser` VARCHAR(1024) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_search_terms_logs' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_search_terms_logs' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_search_terms_logs': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_search_terms_logs
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_search_logs_keyword` ON `ec_search_terms_logs` (`keyword`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_search_logs_keyword' created on 'ec_search_terms_logs'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_search_logs_keyword' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_search_logs_keyword': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_search_logs_customer_id` ON `ec_search_terms_logs` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_search_logs_customer_id' created on 'ec_search_terms_logs'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_search_logs_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_search_logs_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_search_logs_created_at` ON `ec_search_terms_logs` (`created_at`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_search_logs_created_at' created on 'ec_search_terms_logs'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_search_logs_created_at' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_search_logs_created_at': " . $e->getMessage() . "\n";
        }
    }

    // ---- Table: ec_wishlist ----
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS `ec_wishlist` (
                `wishlist_id` INT AUTO_INCREMENT PRIMARY KEY,
                `customer_id` INT NOT NULL DEFAULT 0,
                `item_id` INT NOT NULL DEFAULT 0,
                `product_price_id` INT NOT NULL DEFAULT 0,
                `site_id` INT NOT NULL DEFAULT 0,
                `display_status` VARCHAR(1) NOT NULL DEFAULT 'Y',
                `deleted_status` VARCHAR(1) NOT NULL DEFAULT 'N',
                `created_at` TIMESTAMP DEFAULT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT `customer_item_unique` UNIQUE (`customer_id`, `item_id`)
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'ec_wishlist' created successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Table 'ec_wishlist' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create table 'ec_wishlist': " . $e->getMessage() . "\n";
        }
    }

    // Indexes for ec_wishlist
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_wishlist_customer_id` ON `ec_wishlist` (`customer_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_wishlist_customer_id' created on 'ec_wishlist'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_wishlist_customer_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_wishlist_customer_id': " . $e->getMessage() . "\n";
        }
    }
    try {
        $sql = "CREATE INDEX IF NOT EXISTS `idx_wishlist_item_id` ON `ec_wishlist` (`item_id`)";
        $pdo->exec($sql);
        echo "✅ Index 'idx_wishlist_item_id' created on 'ec_wishlist'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "ℹ️  Index 'idx_wishlist_item_id' already exists – skipping.\n";
        } else {
            echo "❌ Failed to create index 'idx_wishlist_item_id': " . $e->getMessage() . "\n";
        }
    }

    echo "\n✅ Migration completed - All tables processed.\n";
}