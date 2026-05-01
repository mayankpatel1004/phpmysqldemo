<?php
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("Access denied. Run this script from command line only.\n");
}

echo "--- Starting Migration ---\n";

include '/opt/lampp/htdocs/phpmysqldemo/connection.php';
migrate($pdo);

function migrate(PDO $pdo)
{
    // Table: items
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS items (
                item_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                item_title VARCHAR(255) DEFAULT NULL,
                item_alias VARCHAR(255) DEFAULT NULL,
                item_parent INT NOT NULL DEFAULT 0,
                item_type VARCHAR(255) DEFAULT NULL,
                item_sections_id VARCHAR(255) DEFAULT NULL,
                item_description TEXT DEFAULT NULL,
                attachment1 VARCHAR(255) DEFAULT NULL,
                attachment2 VARCHAR(255) DEFAULT NULL,
                item_shortdescription TEXT DEFAULT NULL,
                user_id INT NOT NULL DEFAULT 0,
                controller VARCHAR(50) DEFAULT NULL,
                action VARCHAR(50) DEFAULT 'index',
                published_at DATE DEFAULT NULL,
                published_end_at DATE DEFAULT NULL,
                meta_title VARCHAR(255) DEFAULT NULL,
                meta_description TEXT DEFAULT NULL,
                created_by INT NOT NULL DEFAULT 0,
                created_by_name VARCHAR(255) DEFAULT NULL,
                created_by_role INT NOT NULL DEFAULT 0,
                display_order INT NOT NULL DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_by INT NOT NULL DEFAULT 0,
                deleted_by_name VARCHAR(255) DEFAULT NULL,
                deleted_time DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'items' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'items': " . $e->getMessage() . "\n";
    }

    // Table: action
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS action (
                action_id INT AUTO_INCREMENT PRIMARY KEY,
                action VARCHAR(255) DEFAULT NULL,
                record_id INT NOT NULL DEFAULT 0,
                table_name VARCHAR(255) DEFAULT NULL,
                record_name VARCHAR(255) DEFAULT NULL,
                created_by INT NOT NULL DEFAULT 0,
                display_order INT NOT NULL DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_by INT NOT NULL DEFAULT 0,
                deleted_by_name VARCHAR(255) DEFAULT NULL,
                deleted_time DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'action' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'action': " . $e->getMessage() . "\n";
    }

    // Table: item_section
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS item_section (
                item_section_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                item_section_parent_id INT NOT NULL DEFAULT 0,
                section_title VARCHAR(255) DEFAULT NULL,
                section_alias VARCHAR(255) DEFAULT NULL,
                item_type VARCHAR(255) DEFAULT NULL,
                description TEXT DEFAULT NULL,
                attachment1 VARCHAR(255) DEFAULT NULL,
                user_id INT DEFAULT 0,
                display_order INT DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                meta_title VARCHAR(255) DEFAULT NULL,
                meta_description TEXT DEFAULT NULL,
                created_by INT NOT NULL DEFAULT 0,
                created_by_name VARCHAR(255) DEFAULT NULL,
                created_by_role INT NOT NULL DEFAULT 0,
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_by INT NOT NULL DEFAULT 0,
                deleted_by_name VARCHAR(255) DEFAULT NULL,
                deleted_time DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'item_section' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'item_section': " . $e->getMessage() . "\n";
    }

    // Table: item_section_relation
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS item_section_relation (
                item_section_relation_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                item_id BIGINT NOT NULL DEFAULT 0,
                section_id BIGINT NOT NULL DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_by INT NOT NULL DEFAULT 0,
                deleted_by_name VARCHAR(255) DEFAULT NULL,
                deleted_time DATETIME DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'item_section_relation' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'item_section_relation': " . $e->getMessage() . "\n";
    }

    // Table: meta_details
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS meta_details (
                meta_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                parent_id INT NOT NULL DEFAULT 0,
                end_points VARCHAR(255) DEFAULT NULL,
                page_title VARCHAR(255) DEFAULT NULL,
                meta_title VARCHAR(255) DEFAULT NULL,
                meta_description VARCHAR(255) DEFAULT NULL,
                sidebar_title VARCHAR(255) DEFAULT NULL,
                sidebar_icon VARCHAR(255) DEFAULT NULL,
                sidebar_order INT NOT NULL DEFAULT 0,
                params VARCHAR(255) DEFAULT NULL,
                is_module SMALLINT NOT NULL DEFAULT 0,
                deleted_status VARCHAR(4) NOT NULL DEFAULT 'N'
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'meta_details' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'meta_details': " . $e->getMessage() . "\n";
    }

    // Table: role
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS role (
                role_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                role_title VARCHAR(255) DEFAULT NULL,
                item_alias VARCHAR(255) DEFAULT NULL,
                item_type VARCHAR(255) NOT NULL DEFAULT 'role',
                display_order INT NOT NULL DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                display_on_listing VARCHAR(1) NOT NULL DEFAULT 'Y',
                show_action_checkbox VARCHAR(1) NOT NULL DEFAULT 'Y',
                allow_delete VARCHAR(1) NOT NULL DEFAULT 'Y',
                created_by INT NOT NULL DEFAULT 0,
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_by INT NOT NULL DEFAULT 0,
                deleted_by_name VARCHAR(255) DEFAULT NULL,
                deleted_time DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'role' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'role': " . $e->getMessage() . "\n";
    }

    // Table: role_access
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS role_access (
                role_access_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                role_id INT DEFAULT 0,
                module_id INT DEFAULT 0,
                grant_add VARCHAR(1) NOT NULL DEFAULT 'N',
                grant_edit VARCHAR(1) NOT NULL DEFAULT 'N',
                grant_delete VARCHAR(1) NOT NULL DEFAULT 'N',
                grant_view VARCHAR(1) NOT NULL DEFAULT 'N',
                display_order INT NOT NULL DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'role_access' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'role_access': " . $e->getMessage() . "\n";
    }

    // Table: site_config
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS site_config (
                config_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                config_title VARCHAR(1024) DEFAULT NULL,
                config_name VARCHAR(1024) DEFAULT NULL,
                config_value TEXT DEFAULT NULL,
                input_type VARCHAR(15) DEFAULT NULL,
                size INT NOT NULL DEFAULT 100,
                maxlength INT NOT NULL DEFAULT 100,
                input_type_title VARCHAR(100) DEFAULT NULL,
                class VARCHAR(100) DEFAULT 'textbox',
                required VARCHAR(1) DEFAULT 'O',
                display_order INT NOT NULL DEFAULT 0,
                comments VARCHAR(255) DEFAULT NULL,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                additional VARCHAR(100) DEFAULT NULL,
                display_on_dashboard VARCHAR(1) NOT NULL DEFAULT 'N',
                display_on_third_party VARCHAR(1) NOT NULL DEFAULT 'N',
                site_config_parent_id SMALLINT NOT NULL DEFAULT 0,
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                root_user_only VARCHAR(1) NOT NULL DEFAULT 'N',
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'site_config' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'site_config': " . $e->getMessage() . "\n";
    }

    // Table: site_config_parent
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS site_config_parent (
                site_config_parent_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL,
                site_config_title VARCHAR(191) NOT NULL,
                display_order INT NOT NULL,
                display_status VARCHAR(1) NOT NULL,
                class VARCHAR(191) NOT NULL,
                deleted_status VARCHAR(1) NOT NULL,
                root_user_only VARCHAR(1) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'site_config_parent' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'site_config_parent': " . $e->getMessage() . "\n";
    }

    // Table: users
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                site_id INT NOT NULL DEFAULT 0,
                site_db VARCHAR(255) DEFAULT NULL,
                user_firstname VARCHAR(255) DEFAULT NULL,
                user_lastname VARCHAR(255) DEFAULT NULL,
                user_name VARCHAR(255) DEFAULT NULL,
                user_email VARCHAR(255) DEFAULT NULL,
                user_password VARCHAR(255) DEFAULT NULL,
                user_token VARCHAR(255) DEFAULT NULL,
                user_photo VARCHAR(255) DEFAULT NULL,
                user_role_id SMALLINT NOT NULL DEFAULT 0,
                is_developer_account VARCHAR(1) NOT NULL DEFAULT 'N',
                allow_delete VARCHAR(1) NOT NULL DEFAULT 'Y',
                created_by INT NOT NULL DEFAULT 0,
                created_by_name VARCHAR(255) DEFAULT NULL,
                created_by_role INT NOT NULL DEFAULT 0,
                web_or_app VARCHAR(4) NOT NULL DEFAULT 'App',
                active_status VARCHAR(25) NOT NULL DEFAULT 'N',
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                deleted_time DATETIME DEFAULT NULL,
                deleted_status VARCHAR(4) NOT NULL DEFAULT 'N',
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'users' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'users': " . $e->getMessage() . "\n";
    }

    // Add column add_1 to users (if not exists)
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN add_1 VARCHAR(255) DEFAULT NULL");
        echo "✅ Column 'add_1' added to 'users' table successfully\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false) {
            echo "❌ Failed to add column 'add_1' to 'users' table: " . $e->getMessage() . "\n";
        } else {
            echo "ℹ️ Column 'add_1' already exists in 'users' table\n";
        }
    }

    // Table: customers
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS customers (
                customer_id INT AUTO_INCREMENT PRIMARY KEY,
                cart_id_pk INT DEFAULT 0,
                cart_customer_id VARCHAR(255) DEFAULT NULL,
                customer_pin INT NOT NULL DEFAULT 0,
                pos_customer VARCHAR(1) NOT NULL DEFAULT 'N',
                name VARCHAR(255) DEFAULT NULL,
                first_name VARCHAR(191) DEFAULT NULL,
                last_name VARCHAR(191) DEFAULT NULL,
                email VARCHAR(100) UNIQUE DEFAULT NULL,
                password VARCHAR(191) DEFAULT NULL,
                birth_date DATE DEFAULT NULL,
                role_id INT NOT NULL DEFAULT 0,
                guest_customer VARCHAR(1) NOT NULL DEFAULT '0',
                access_token VARCHAR(255) DEFAULT NULL,
                security_question_id INT NOT NULL DEFAULT 0,
                security_answer VARCHAR(191) DEFAULT NULL,
                user_address1 VARCHAR(191) DEFAULT NULL,
                user_address2 VARCHAR(191) DEFAULT NULL,
                user_city VARCHAR(191) DEFAULT NULL,
                user_state VARCHAR(191) DEFAULT NULL,
                user_zipcode VARCHAR(191) DEFAULT NULL,
                user_country VARCHAR(191) DEFAULT NULL,
                contact_number VARCHAR(191) DEFAULT NULL,
                display_on_listing VARCHAR(1) NOT NULL DEFAULT 'Y',
                show_action_checkbox VARCHAR(1) NOT NULL DEFAULT 'Y',
                web_token VARCHAR(255) DEFAULT NULL,
                api_token VARCHAR(255) DEFAULT NULL,
                session_id VARCHAR(255) DEFAULT NULL,
                device_id VARCHAR(255) DEFAULT NULL,
                device_name VARCHAR(255) DEFAULT NULL,
                item_type VARCHAR(25) NOT NULL DEFAULT 'users',
                wallet_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                display_order INT NOT NULL DEFAULT 0,
                display_status VARCHAR(1) NOT NULL DEFAULT 'Y',
                blocked VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_status VARCHAR(1) NOT NULL DEFAULT 'N',
                deleted_by INT NOT NULL DEFAULT 0,
                deleted_by_name VARCHAR(255) DEFAULT NULL,
                deleted_time DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL
            )
        ";
        $pdo->exec($sql);
        echo "✅ Table 'customers' created successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create table 'customers': " . $e->getMessage() . "\n";
    }

    echo "\n✅ Migration completed - All tables processed.\n";
}