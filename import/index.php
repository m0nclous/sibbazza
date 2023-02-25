<?php

require_once '../wp-config.php';

$nomenclature = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><nomenclature />');
$json = json_decode(file_get_contents('response.json'), true);

function getCategories($json, $id, $categories = [])
{
    foreach ($json['nomenclatures'] as $item) {
        if ($item['hierarchicalId'] !== $id) continue;

        $categories[] = $item['name'];

        if ($item['hierarchicalParent']) {
            $categories = getCategories($json, $item['hierarchicalParent'], $categories);
        }
    }

    return $categories;
}

foreach ($json['nomenclatures'] as $item) {
    if ($item['isParent']) continue;
    if (!$item['published']) continue;

    $product = $nomenclature->addChild('product');
    $product->addChild('id', $item['id']);
    $product->addChild('guid', $item['externalId']);
    $product->addChild('name', $item['name']);
    $product->addChild('price', $item['cost']);
    $product->addChild('categories', implode(' > ', getCategories($json, $item['hierarchicalParent'])));

    $images = $product->addChild('images');
    foreach ($item['images'] ?? [] as $image) {
        $images->addChild('image', APP_URL . "/import$image");
    }
}

header('Content-Type: text/xml');
exit($nomenclature->asXML());