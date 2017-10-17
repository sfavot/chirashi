#!/usr/local/bin/php -q

<?php

include __DIR__.'/vendor/autoload.php';

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

const URL = 'https://www.frichti.co';
const API_URL = 'https://api-gateway.frichti.co/kitchens/2/menu';
const KEYWORDS = ['chirashi'];

function getMenu(): \stdClass
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, API_URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	$data = curl_exec($ch);
	curl_close($ch);

	return json_decode($data);
}

function sendNotification(string $dishes): void
{
    $notifier = NotifierFactory::create();
    
    $body = count($dishes) > 1
        ? $dishes.' sont disponibles sur Frichti.'
        : $dishes.' est disponible sur Frichti.'
    ;

    $notification =
        (new Notification())
        ->setTitle('Votre plat est disponible sur Frichti !')
        ->setBody($body)
        ->setIcon(__DIR__.'/assets/yum.png')
        ->addOption('url', URL)
    ;
    
    $notifier->send($notification);
}

function process(): void
{
    $menu = getMenu();
    
    $found = [];
    
    foreach ($menu->menu as $cat) {
        foreach ($cat->collects as $dish) {
            foreach (KEYWORDS as $keyword) {
                $keyword = strtolower($keyword);
                if (isset($dish->products) && strpos(strtolower($dish->products->title), $keyword) !== false) {
                    $found[] = $dish->products->title;
                }
            }
        }
    }
    
    $found = array_unique($found);
    
    if (count($found) > 0) {
        sendNotification(implode(', ', $found));
    }    
    
    exit('Frichti checked on '.date('d/m/Y h:i:s a').'.');
}

process();

?>
