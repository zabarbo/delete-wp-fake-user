# delete-wp-fake-user

<p>This plugin is a WordPress tool designed to streamline user management, especially for websites using WooCommerce. Below is an overview of its features:</p>

<h2>Features</h2>

<ul>
    <li>
        <strong>Count Users Without Name and Orders</strong>
        <p>This feature identifies users who have not provided a name and have not made any WooCommerce orders.</p>
        <p>A shortcode <code>[umh_user_count]</code> can be used to display this count on the frontend.</p>
    </li>
    <li>
        <strong>List Users Without Name and Orders</strong>
        <p>Generates a table of users missing both a name and WooCommerce orders. Only users meeting these criteria are displayed to help administrators focus on incomplete profiles.</p>
        <p>Use the shortcode <code>[umh_user_list]</code> to embed this table on any page or post.</p>
    </li>
    <li>
        <strong>Delete Users Without Name and Orders</strong>
        <p>Deletes users who meet the following conditions:</p>
        <ul>
            <li>No name provided.</li>
            <li>No orders placed in WooCommerce.</li>
        </ul>
        <p>This feature ensures that administrators or users with specific roles are not deleted. Before deletion, a confirmation dialog warns administrators to back up their database.</p>
    </li>
    <li>
        <strong>Export Users to CSV</strong>
        <p>Allows exporting all registered users to a CSV file as a backup. The file includes user ID, name, email, and roles. This is useful for maintaining records before performing deletions.</p>
    </li>
</ul>

<h2>Admin Page Interface</h2>
<p>An admin menu item is added to manage all functionalities:</p>
<ul>
    <li>Display the count of users without a name and orders.</li>
    <li>List users who meet the criteria.</li>
    <li>Delete users with a warning prompt and CSV backup option.</li>
    <li>Export all users to a CSV file for safekeeping.</li>
</ul>
<h2>Technical Requirements</h2>
<p>To ensure proper functionality of the <strong>User Management Helper</strong> plugin, your system must meet the following requirements:</p>

<ul>
    <li>
        <strong>WordPress Version:</strong> 5.8 or higher  
        <em>(Recommended: Always use the latest stable version).</em>
    </li>
    <li>
        <strong>WooCommerce Version:</strong> 6.0 or higher  
        <em>(Required for order-related functionalities).</em>
    </li>
    <li>
        <strong>PHP Version:</strong> 7.4 or higher  
        <em>(Recommended: PHP 8.0 or higher for improved performance and security).</em>
    </li>
    <li>
        <strong>Database:</strong> MySQL 5.7 or higher / MariaDB 10.3 or higher  
        <em>(Ensure your database is compatible with WordPress and WooCommerce).</em>
    </li>
    <li>
        <strong>Server Requirements:</strong>  
        <ul>
            <li>HTTPS support</li>
            <li>cURL enabled</li>
            <li>JSON support</li>
        </ul>
    </li>
    <li>
        <strong>User Roles:</strong> The plugin requires administrative privileges to access and modify user data.</li>
</ul>

<p>Before installing the plugin, verify that your hosting environment satisfies these requirements to avoid compatibility issues.</p>
