User Submission and Notification Plugin
---------------------------------------

**Description**

This plugin empowers your WordPress website to accept user-generated content through a user-friendly front-end form. Submitted information is stored as a custom post type named "Submissions" and an email notification is sent to the administrator (or a custom email address if configured).

**Features**

-   **User-Friendly Form:** Visitors can easily submit content using a shortcode placed on any page or post.
-   **Customizable Notification Email:** The email sent to the administrator includes details like title, content, and submitter's email.
-   **Admin Settings:** Manage notification email address and other plugin configurations from a dedicated settings page.
-   **Secure Nonce Verification:** Protects the form from unauthorized submissions.
-   **Sanitized Data:** Ensures submitted data is clean and secure before processing.

**Installation**

1.  Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin from the WordPress admin panel (Plugins > Installed Plugins).

**Usage**

1.  Navigate to the "Settings" menu and locate the "User Submission and Notification" submenu.
2.  Configure your preferred notification email address (optional).
3.  Use the shortcode `[sbm_notif_shortcode]` on any page or post where you want the submission form to appear.
4.  Visitors can then submit their content through the form.
5.  You'll receive an email notification for each successful submission.

**Additional Notes**

-   This plugin creates a custom post type named "Submissions" to store submitted content.
-   You can manage submitted content from the WordPress admin panel (Posts > Submissions).

**Support**

For any questions or assistance, feel free to reach out to the plugin developer (replace with your contact information).

**Version History**

-   Version 1.0.0: Initial release

**License**

This plugin is licensed under the GNU General Public License (GPL) v2 or later. You can find a copy of the license here: <https://www.gnu.org/licenses/gpl-3.0.en.html>