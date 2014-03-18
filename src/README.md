kiosker-config
==============

This is a companion project to [Kiosker](https://github.com/mofus/Kiosker).

It allows admins and end-users to configure Kiosker-powered devices.

# Installation

1. Copy to a location served by a PHP daemon
2. Set the permissions of the `configs` sub-directory so that your web server can write to it (how to do this varies on how your web server is configured to run)

# Configuration
First you need to set the admin password. This is the password required to log in and edit devices.

1. Open `hash.php` in your browser (eg http://yourhost.com/kiosker/hash.php)
2. Put in some random text as the salt value. You can [generate random characters here](http://thestaticvoid.net/toy/rand/).
3. Type in a password, and click 'Generate'
4. Open `config/base.json` in a plain text editor, and paste in your salt value into 'passwordSalt', and the generated hash value into 'passwordHash'
5. Do the same for 'masterPasswordHash' and 'masterPasswordSalt' if you want to use the same admin password to unlock all Kiosker devices.

# Test
Access Kiosker Config in your browser (eg http://yourhost.com/kiosker/), and try logging in with username 'admin' and the password you created in the configuration step. You should see an administrator menu.

# Provisioning a new device
1. Log in to Kiosker Config
2. Click "Create config"
3. Type in the device name. This must be the same name which is set inside the Kiosker app on the Android device.
4. Click 'Create'. A new configuration file will be created, using `deviceTemplate.json` as the starting point. 
5. Edit the new configuration file from the administration menu, and set a `passwordSalt` and `passwordHash` values. Use the 'Generate Hash' admin option. You are can re-use the same salt value across all devices or set it per-device for improved security.
6. Now that password is set, refresh the configuration on the Android device. The new password should now unlock the device and also permit configuration via web app.

# For end users
1. Log in to Kiosker Config using your provided device name and password
2. Make necessary changes and click 'Save'
3. Your device will automatically fetch the new settings after a period of time, or you can access the Kiosker menu and request a manual refresh.

Read more
* [Configuring Kiosker](https://github.com/mofus/Kiosker)