<?php

namespace CodeSoup\Certify;

// Exit if accessed directly
defined( 'WPINC' ) || die;

class EmailCustomer {

	use Traits\HelpersTrait;

    private $to;
    private $subject;
    private $body;
    private $headers;

    public function __construct($args = []) {

    	$params = wp_parse_args( $args, [
			'recepient' => '@',
			'subject'   => 'Email Subject',
			'headers'   => [],
			'mailer'    => $this,
    	]);
		
		$this->to      = $params['recepient'];
		$this->subject = $params['subject'];
		$this->body    = $this->prepareEmailBody($params);
		$this->headers = array_merge(['Content-Type: text/html; charset=UTF-8'], $params['headers']);
    }

    private function prepareEmailBody($variables = [])
    {
	    $templatePath = $this->get_plugin_dir_path('templates/email/new-license/index.php');

	    if (!file_exists($templatePath)) {
	        return false;
	    }


	    // Extract variables to be available in the template scope.
	    extract($variables);

	    // Start output buffering.
	    ob_start();

	    // Include the template file.
	    include $templatePath;

	    // Get the contents of the buffer and end buffering.
	    return ob_get_clean();
	}


    public function send()
    {
        if (!$this->body) {
            return false;
        }

        return wp_mail($this->to, $this->subject, $this->body, $this->headers);
    }
}
