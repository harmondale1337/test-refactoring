<?php
	
/**
 * @author Gaultier D'ACUNTO <gaultier.dac@gmail.com>
 *
 * Class TemplateGenerator 
 * Takes the parsed YAML file (array format) to construct the final message to send  
 * It will create mail's headers regarding content found
 *
 */
Class TemplateGenerator{
	
    /**
     * @var private $toSend : array
     * returns the mail's subject, content and headers 
     *
     */
    private $toSend = [];
	
    /**
     * Class constructor
     */
    public function __construct($parsed){
	
        $this->parsed = $parsed;
		
    }
		
    /**
     * Fetches the targeted datas in the template YAML file and add signature if it exists
     */ 
    public function createMessage($origin,$keyword,$lang,$signature){

        if($origin === ''){
            $origin = 'mail';
        }
        // Verifies if all the informations exists 
        self::structureVerification($this->parsed,$origin,$keyword,$lang);

        $sub = $this->parsed[$origin][$keyword][$lang]['subject'];
        $content = $this->parsed[$origin][$keyword][$lang]['content'];

        $toSend['sub'] = $this->parsed[$origin][$keyword][$lang]['subject'];
        $toSend['content'] = $this->parsed[$origin][$keyword][$lang]['content'];
        $toSend['headers'] = self::createHeaders($toSend['content'],$this->parsed['emitter'][$signature]['infos']);
			
        if($signature != 'none'){
            $signature = $this->parsed[$origin][$keyword][$lang]['signature'];
            foreach($this->parsed['emitter'][$signature]['content'] as $signList){
                $toSend['content'] .= $signList."\n";
            }
        }

        return $toSend;
    
    }

    /**
     * Switches headers to apply html or plain text Content-Type if HTML special characters are found in the content
     * Applies Senders informations if mentioned in the Template file
     */
    private function createHeaders($content,$sender){

        $typeBool = preg_match('/\/>/',$content);
        $headers  = 'MIME-Version: 1.0' . "\n";
			
        if($typeBool == true){
            $source = 'html';
        }else{
            $source = 'plain';
        }

        $headers .= 'Content-Type: text/'.$source.'; charset="iso-8859-1"'."\n";
        $headers .= 'Reply-to: '.$sender['name'].' <'.$sender['mail'].'>'."\n" ;
        $headers .= 'Return-path: '.$sender['name'].'<'.$sender['mail'].'>'."\n" ;
        $headers .= 'From: '.$sender['name'].' <'.$sender['mail'].'>'."\r\n";

        return $headers;

    }

    /**
     * Verify if the keys provided exists and does not contain empty values 
     */
    private function structureVerification($parsedYaml,$origin,$keyword,$lang){

        $toTest = [$origin,$keyword,$lang];
        $required = ['subject','content'];
        $index = $parsedYaml;
        $i = 0;
			
        foreach($toTest as $test){
            if(!array_key_exists($test,$index)){
                throw new \RuntimeException("$test key does not exists in the YAML file for this tree structure");
            }else{
                $index = $index[$test];
            }
            // Check if the last item contains an array with a subject and a content 
            if(++$i == 3){
                if(array_diff($required, array_keys($index))){
                    throw new \RuntimeException("$test array needs to have the required keys to be sent (subject, content ...)");
                }else{
                    foreach($index as $emptyControl){
                        if(empty($emptyControl) || $emptyControl == ''){
                            throw new \RuntimeException("There is an empty value in this project");
                        }
                    }
                }	
            }
        }
						
    }

}
