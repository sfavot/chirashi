# Chirashi

If you don't want to subscribe to Frichti newsletters but still want to know if your favorite dishes are available on [Frichti](http://www.frichti.co).

## Usage

When run, the script will check if the specified dishes are available in today's menu, then display a notification if it's the case.

## Setting up

Change the KEYWORDS constant in chirashi.php to add the dish or dishes you want.
Check which Frichti kitchen is around you by typing your delivery address in www.frichti.co and checking which api call is made. The url should be something like: https://api-gateway.frichti.co/kitchens/2/menu.
Replace it in the API_URL constant in chirashi.php.

To run the script:

```
./script.sh
```

To use it as a cron, edit your crontab with:

```
crontab -e
```

then add the following line:

```
0 11,19 * * * <path-to-project>/script.sh > <path-to-output-file>
```

