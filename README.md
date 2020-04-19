# Refactoring Kata Test - Gaultier D'ACUNTO



## Main stages of the project



**Initial environment setup**
- Fetch and install Composer
- Changed the faker library version in composer.json from 1.6.1 to 1.6.0 
- Installed php-xml & mb-string to run properly the ./composer.phar install


**Analysis of the project**
- Comprehension of class interactions
- Reflexion on how to make the code more understandable, with on top of that an externalized structure to provide template (to be usable in other components if needed in the future)
- Isolation of classes / files that doesn't require modification
- Detection of traps in the code
- Checking of what we can use from `./vendor`


**Explaination `./src/template_config.yaml` template file**

I chose to centralized every templates inside only one file. I could have used JSON too, but there was already the YAML Parser in the `./vendor`, so I went with this. 
The target of this file is to provide flexibles, evolutionary and reusable parts on the project. 
With this file, the developer can add a new language or add a new mail content with ease, and attach it to a dissociated signature (dissociated with the "reply to" mail address attached too). Thanks to that, for example, **2 departments can use the same email template if needed and attach their own signature to it**.

This template doesn't fit only for email, if any other type of message has to be implemented, the developer can add another part outside the **`mail`** item to begin to improve this feature.


**Explaination `./src/Core/TemplateGenerator.php` script file**

The TemplateGenerator is used to parse the `./src/template_config.yaml` file and extract the needed informations. When the Generator has found the correct keys, it generates the headers thanks to the mail **`content`** (by a regex, if **`"/>"`** is found in it).
When headers are generated, a final array is created containing : subject, content, signature (if mentioned) and headers.


**Explaination `./src/Core/TemplateManager.php` script file**

Here are the actions made on this class : 

- Renamed the result variables that we fetch from Repositories to make it more usable when called from the template as **`variable->property`**
- Renamed **`useFulObject`** with a name that represents what it actually does
- Added a regex which can parse any variable call provided in the Manager, and deleted the **`strpos()`** that limited a lot in-depth developments 
- Separated the process in precise methods and used the Oriented Object environment to store dynamically the final text into the object properties (with the elements retrieved by Repositories)


**Code securization / highlight / fixes**

- Errors handlers
- Comments on the mainly modified classes
- Small fixes (regarding YAML file and Generator script) realized after many different tests


**Unit tests creation**

The Tester environment tests :  
- if the YAML file contains the expected structure
- if the content & subject of each mail is a string and not empty
- if the final generated array contains the three essential keys : subject, content, headers
- if there isn't still an unprocessed variable remaining in the content


**Last minutes stupid modifications**

I refactored the project on my own server. When finished, I saw 2 really small & useless improvments to do, and to be quicker I did it with vim (to avoid redeployments etc.., I think I saved something like 20 seconds...), which was a mistake, because it unstructured the indentation. Not a major issue at all, but now I have to reindent properly TemplateManager & TemplateGenerator.



--------------------------------------------------------------------



## Why is it better to use a YAML / JSON file for this instead of putting the text in the PHP file ?

- First rule ever is to avoid hard content in scripts
- Possibility to use this class from other projects
- Ease to manage maintenance / run unit tests as every information are centralized
- If a modification is needed on a message used [X] times in other classes, it will be passed to those ones. 



## Why is this new project evolutive ?

- Possibility to add languages / other message types / any feature while maintaining the same processing method
- TemplateManager is now processing every variable type, not only the ones defined (and raise an error if not found)
- If needed, we can demultiply the processing scripts targeting the same YAML for other purposes
- Unit tests are now expecting that every fields are well filled (instead of doing always the same hardcoded test in the previous version)



## What could I improve if I had more time to spent on the project (and if useful)

Many things, but here is a short list :

**Email :** 
- Attached files management for emails (via Entity / Repositories)
- Languages / Attached files in signatures
- Signature emptiness management
- Verify if mails are correctly sent

**Unit Tests :**
- Agnostic unit tests 
- Export all the components used during the process (in Entity / Repositories) and reproduce the behavior of TemplateManager / TemplateGenerator. In parallel, execute the query by the TemplateManager / TemplateGenerator to find if the ./Core files produce the exact same thing as the result obtained by executing the components step by step.



## What were the difficulties encoutered during the project ? 

**Expected result was very blur which means : ** 

- Don't know what is the supposed size of the project (is it just a small part to quickly send mail, or is it a big feature that will be used really often by the future ?)
- Don't know when to really stop to improve the project (as the concrete refactoring is done in 1 hour, but it still misses many improvments to make the entire project more clear, and error handling can be more and more securized)
- Check for little mistakes inside the codes (like useFulObject etc..)
- Keeping a maximum of existing code to save time, even if it doesn't match with the way I develop (every dev prefers to have an harmonized code)



## Was it cool to do this refactoring ? 

***Yes, really !***

