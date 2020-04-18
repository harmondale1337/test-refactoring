# Refactoring Kata Test

## Credits

This test kata is a fork from the original test kata by [Theodo](https://www.theodo.fr/).

## Introduction

We have some message templates we want to send in different languages. To do that, we've developed
 `TemplateManager` whose job is to replace placeholders in texts by travel related information.

`TemplateManager` is a class that's been around for years and nobody really knows who coded
it or how it really works. Nonetheless, as the business changes frequently, this class has
already been modified many times, making it harder to understand at each step.

Today, once again, the PO wants to add some new stuff to it and add the management for a new
placeholder. But this class is already complex enough and just adding a new behaviour to it
won't work this time.

Your mission, should you decide to accept it, is to **refactor `TemplateManager` to make it
understandable by the next developer** and easy to change afterwards. Now is the time for you to
show your exceptional skills and make this implementation better, extensible, and ready for future
features.

Sadly for you, the public method `TemplateManager::getTemplateComputed` is called everywhere, 
and **you can't change its signature**. But that's the only one you can't modify (unless explicitly
forbidden in a code comment), **every other class is ready for your changes**.

This exercise **should not last longer than 1,5 hour** (but this can be too short to do it all and
you can take longer if you want).

You can run the example file to see the method in action.

## Rules
There are some rules to follow:
 - You must commit regularly
 - You must not modify code when comments explicitly forbid it

## Deliverables
What do we expect from you:
 - the link of the git repository
 - several commits, with an explicit message each time
 - a file / message / email explaining your process and principles you've followed

## Basic info for php beginners
- You can take more time because it can be more difficult to understand the code
- To install dependencies use [compooser](https://getcomposer.org/doc/00-intro.md#locally), `./composer.phar install`
- To run the tests (after dependencies are isntalled) `vendor/bin/phpunit`
- If you encounter difficulties because of php, a particular focus will be expected on concepts that could improve the code to discuss it during the interview

**Good luck!**
