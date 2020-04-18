<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../src/Entity/Destination.php';
require_once __DIR__ . '/../src/Entity/Quote.php';
require_once __DIR__ . '/../src/Entity/Site.php';
require_once __DIR__ . '/../src/Entity/Template.php';
require_once __DIR__ . '/../src/Entity/User.php';
require_once __DIR__ . '/../src/Helper/SingletonTrait.php';
require_once __DIR__ . '/../src/Context/ApplicationContext.php';
require_once __DIR__ . '/../src/Repository/Repository.php';
require_once __DIR__ . '/../src/Repository/DestinationRepository.php';
require_once __DIR__ . '/../src/Repository/QuoteRepository.php';
require_once __DIR__ . '/../src/Repository/SiteRepository.php';
require_once __DIR__ . '/../src/Core/TemplateManager.php';
require_once __DIR__ . '/../src/Core/TemplateGenerator.php';

$faker = \Faker\Factory::create(); 
$parsed = Yaml::parseFile(__DIR__.'/../src/template_config.yaml');

$message = new TemplateGenerator($parsed);
$processed = $message->createMessage('mail','thanks','en','default');

$template = new Template(1,$processed['sub'],$processed['content']);
$templateManager = new TemplateManager();
$message = $templateManager->getTemplateComputed(
    $template,
    [
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->subject . "\n" . $message->content;
//mail($to,$message->subject,$message->content,$processed['headers']);
