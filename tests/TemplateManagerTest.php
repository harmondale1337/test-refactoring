<?php

    require_once __DIR__ . '/../vendor/autoload.php';
    use PHPUnit\Framework\TestCase;
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


    class TemplateManagerTest extends TestCase
    {
	
	/**
	 * TemplateManagerTest Constructor
	 */
        public function __construct(){

            $this->faker = \Faker\Factory::create();
            $this->expectedDestination = DestinationRepository::getInstance()->getById($this->faker->randomNumber());
            $this->expectedUser = ApplicationContext::getInstance()->getCurrentUser();
	    $this->parsed = Yaml::parseFile(__DIR__.'/../src/template_config.yaml');


	    // Final array generation
	    $message = new TemplateGenerator($this->parsed);
            $this->processed = $message->createMessage('mail','thanks','en','default');
            $template = new Template(1,$this->processed['sub'],$this->processed['content']);
            $templateManager = new TemplateManager();
            $this->message = $templateManager->getTemplateComputed(
                $template,
                [
                    'quote' => new Quote($this->faker->randomNumber(), $this->faker->randomNumber(), $this->faker->randomNumber(), $this->faker->date())
                ]
            );

        }


        /**
         * Tests if the YAML contains the mail key
         */
        public function testFetchYaml(){

            $this->assertArrayHasKey('mail', $this->parsed, "mail key not found in array");

        }



        /**
         * Tests if every mail template contains a proper subject and content in string format
         */
        public function testMailSubjectAndContent(){

            $testTargetedKey = $this->parsed['mail'];

            foreach($testTargetedKey as $t => $test){
                foreach($test as $fRow){

                    $this->assertInternalType('string', $fRow['subject'], "Type is : ".gettype($fRow['subject'])." instead of a string in ".$t);
                    $this->assertInternalType('string', $fRow['content'], "Type is : ".gettype($fRow['content'])." instead of a string in ".$t);

                }
            }

        }


        /**
         * Tests if the function returns at the end an array containing 'subject', 'content' & 'headers'
         */
        public function testExpectationResult(){

            $notExpected = "/\[[a-z0-9]{1,}:[a-z0-9_]{1,}\]/i";

            $this->assertArrayHasKey('sub', $this->processed, "sub key not found in final mail array");
            $this->assertArrayHasKey('content', $this->processed, "content key not found in final mail array");
            $this->assertArrayHasKey('headers', $this->processed, "headers key not found in final mail array");

            // Verifies if the final message does not contains variables anymore (replaced text expected)
            $this->assertNotRegExp($notExpected,$this->message->content,"Variables were not correctly processed / computed by the TemplateManager");            

        }


        /**
         * Send email to the admin (not included in test)
         */
        public function testSendMail(){

        

        }


    }

