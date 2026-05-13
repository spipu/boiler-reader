# Boiler-reader

A Symfony application running on a Raspberry Pi that reads data from a **Hargassner biomass boiler**
via TCP/Telnet, buffers it locally in MariaDB and pushes it to a remote server.

## How it works

1. Connects to the boiler's Telnet interface (TCP port 23) every minute via cron
2. Reads the data frame (lines prefixed with `pm`, space-separated values)
3. Stores it temporarily in a local buffer (MariaDB)
4. Pushes it to a remote HTTP API (SHA-1 signed POST)
5. Deletes the entry from the buffer after a successful push

> Unlike a data logger, boiler-reader is a **relay** — data is deleted after push.
> The remote server is the permanent storage.

## Requirements

- Raspberry Pi 3
- Hargassner boiler equipped with **Touch Control** or **Nano-PK** controller, connected to the local network
- The boiler must be reachable at a fixed IP address on the local network (Telnet port 23)

## Hargassner Protocol

The Hargassner Touch Control / Nano-PK exposes an **undocumented Telnet interface** on port 23.
The boiler continuously sends data frames as lines prefixed with `pm` followed by 100–228
space-separated numerical values (exact count depends on firmware version).

The meaning of each index is firmware-dependent and not officially documented by Hargassner.
It can be retrieved from the XML configuration file (DAQPRJ format) on the boiler's SD card.

Example frame:
```
pm 1 1.2 7.5 54 0 52 6 6 64 -20 52 ...
```

## Install

See [INSTALL.md](./INSTALL.md) for the full installation procedure.

## Test

```bash
sudo -u www-data /var/www/boiler-reader/website/bin/console app:boiler:run
```

## Push to a server

Data is pushed to a remote HTTP API. Configure the endpoint, API name and API key in the admin
panel (Admin → Configuration → Boiler Reader).

The push uses a SHA-1 signature: `sha1(username|request_time|data_time|rand|values|api_key)`.

## Sources

* https://jahislove314.wordpress.com/2016/12/09/lire-les-donnees-dune-chaudiere-hargassner-par-telnet-en-python-2-7/
* https://github.com/Jahislove/hargassner-python
* https://github.com/Jahislove/Hargassner
* https://community.home-assistant.io/t/hargassner-heating-integration/288568
* https://github.com/schuppenkarp/Hargassner_NodeJS_Adapter
