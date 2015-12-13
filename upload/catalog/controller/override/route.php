<?php
class ControllerOverrideRoute extends Controller {
	public function index() {
		if (isset($request->get['route'])) {
			$route = $request->get['route'];
		} else {
			$route = 'common/home';
		}
		
		// Sanitize the call
		$route = str_replace('../', '', (string)$route);
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/before', $args);
		
		if (!is_null($result)) {
			return $result;
		}
		
		array_shift($args);
		
		$action = new Action($route);
		$output = $action->execute($this->registry, $args);
			
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/after', array(&$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;
	}
}