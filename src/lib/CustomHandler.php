<?php
class CustomHandler {
   public function __invoke($request, $response, $exception) {
   	//Log the error in file    	print_r($exception->getMessage());
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode('Something went wrong!'));
   }
}
?>