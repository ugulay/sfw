<?php

define("ROOT", __DIR__);

ob_start();

error_reporting(0);
ini_set('error_reporting', 0);

require ROOT . "/Kernel/Autoload.php";

/**
 * ENV Loader
 */
$dotenv = Dotenv\Dotenv::createImmutable(ROOT, ".env", true, "UTF-8");
$dotenv->load();

?>

<form action="?install=1" method="POST" style="margin:auto; width:100%;">
    <h3>Create a root user</h3>
    <input type="text" name="username" required placeholder="Username" value="<?= @$_POST["username"] ?>"></br>
    <input type="email" name="email" required placeholder="Email" value="<?= @$_POST["email"] ?>"></br>
    <input type="password" name="password" required placeholder="Password" value="<?= @$_POST["password"] ?>"></br>
    <button type="submit">Start install</button>
</form>

<?php

if ($_POST && $_GET["install"] == 1) {

    if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"])) {
        echo "Username, Email or Password can not be blank." . PHP_EOL;
        return false;
    }

    $password = hash_hmac('sha512', trim($_POST["password"]), $_ENV["SECRET_KEY"]);

    $database = \Kernel\Database::getInstance();

    // Create Account Table;
    $database->create("accounts", [
        "id" => ["INT", "UNSIGNED", "NOT NULL", "AUTO_INCREMENT", "PRIMARY KEY"],
        "username" => ["VARCHAR(64)", "NOT NULL", "UNIQUE"],
        "email" => ["VARCHAR(255)", "NOT NULL", "UNIQUE"],
        "password" => ["VARCHAR(255)", "NOT NULL"],
        "activation_code" => ["VARCHAR(255)"],
        "status" => ["TINYINT(1)", "NOT NULL"],
        "created_at" => ["DATETIME"],
        "updated_at" => ["DATETIME"]
    ]);

    // Create Blog Table;
    $database->create("posts", [
        "id" => ["INT", "UNSIGNED", "NOT NULL", "AUTO_INCREMENT",  "PRIMARY KEY"],
        "title" => ["VARCHAR(255)", "NOT NULL"],
        "slug" => ["VARCHAR(255)", "NOT NULL", "UNIQUE"],
        "user" => ["INT", "NOT NULL"],
        "content" => ["TEXT", "NOT NULL"],
        "status" => ["TINYINT(1)", "NOT NULL"],
        "created_at" => ["DATETIME"],
        "updated_at" => ["DATETIME"]
    ]);

    // Add Admin to Accounts table
    $database->insert("accounts", [
        "username" => $_POST["username"],
        "email" => $_POST["email"],
        "password" => $password,
        "status" => 2
    ]);

    echo "<pre>";
    print_r($database);
    echo "</pre>";

    // Database created message
    echo "Database created succesfully" . PHP_EOL;
}
