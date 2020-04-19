<?php


/**
 * Class TemplateGenerator 
 * Fetches and parses the variables from the Repositories 
 */
Class TemplateManager
{

    public function getTemplateComputed(Template $tpl, array $data){

        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

	return $replaced;

    }

    private function computeText($text, array $data){

        $APPLICATION_CONTEXT = ApplicationContext::getInstance();
        $this->text = $text;

        // Get the user data and the quote
        $this->user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        $_quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        // Retrieve all infos of the quote
        $this->quote = QuoteRepository::getInstance()->getById($_quote->id);
        $this->site = SiteRepository::getInstance()->getById($this->quote->siteId);
        $this->destination = DestinationRepository::getInstance()->getById($this->quote->destinationId);

        // Get all matched variable call in the template
        $this->getMatches();

        /**
         * Match in the template all infos available with the quote
         * for example you can match [destination:countryName]
         * and add them to the final text
         */
        $this->parseMessage();

        return $this->text;

    }


    /**
     * Get called variables in the template
     */
    private function getMatches(){

        $regex = '/\[[a-z0-9]{1,}:[a-z0-9_]{1,}\]/i';
        preg_match_all($regex, $this->text, $matches);

        $this->matches = $matches[0];
			
    }

		
    /**
     * Parse & replace message content 
     */
    private function parseMessage(){

        foreach($this->matches as $value){

            $split = explode(':', preg_replace('/\[|\]/', '', $value));
            $entity = $split[0];
            $property = $split[1];

            if(!isset($this->$entity)){
                throw new \RuntimeException("$entity is not defined");
            }elseif(!isset($this->$entity->$property)){
                throw new \RuntimeException("property $property of $entity does not exist");
            }

            $this->text = str_replace($value, $this->$entity->$property, $this->text);

	}

    }
    
}
