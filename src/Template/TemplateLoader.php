<?php namespace Wn\Generators\Template;

use CommentToCode\Parsers\Exceptions\ParserException;
use CommentToCode\Generators\BaseGenerator;
use CommentToCode\Parsers\PHPParser;
use Illuminate\Filesystem\Filesystem;
use Wn\Generators\Exceptions\TemplateException;


class TemplateLoader {
	
	protected $fs;

	protected $loaded;

	public function __construct(Filesystem $fs)
	{
		$this->fs = $fs;
		$this->loaded = [];
	}

	public function load($name)
	{
		if(! isset($this->loaded[$name])){
			$path = config("lumen-generators.custom_templates.{$name}", __DIR__ . "/../../templates/{$name}.php.template");
			
			try {
			    $parser = new PHPParser();
			    $parser->setTemplate($path);
			    
				$this->loaded[$name] = new BaseGenerator($parser);
            } catch(ParserException $e) {
                throw new TemplateException("File did not exist '{$path}'");
            } catch(\Exception $e) {
                throw new TemplateException("Unable to read the file '{$path}'");
			}
		}
		return new Template($this, $this->loaded[$name]);
	}

}
