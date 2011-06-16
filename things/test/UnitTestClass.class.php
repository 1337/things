<?php
    class UnitTestClass {   
    
        /*  UnitTestClass (CC 3.0, MIT) 2011 Brian Lai
            
            How to use:
            
            make a new class e.g. "MyTests"
            
            class MyTests extends UnitTestClass {
                function test_1 () {
                    $this->assertTrue (true);
                }
            }
            
            ALL functions with "test_" prefix will be run.
            
        */
    
        private $error;
        public $resultstack;
    
        function UnitTestCase () {
            $this->__construct ();
        }
    
        function __construct () {
            // get all method names in this class.
            $this->resultstack = array ();
            $this->setup ();
            error_reporting (E_ALL);
            $methods = get_class_methods (get_class ($this));
            foreach ($methods as $method) {
                if (substr ($method, 0, 5) == "test_") {
                    try {
                        $this->$method ();
                        // ob_flush (); // optional; makes the tests look dynamic
                    } catch (Exception $e) {
                        // fail loudly
                    }
                }
            }
            $this->teardown ();
        }
        
        function setup () {
            // extend me to run things before the test.
        }

        function teardown () {
            // extend me to run things this the test.
        }
        
        function __destruct () {
            // do you...
        }
            
        function cyberpolice () {
            // backtraces. consequences will never be the same
            $trace = array_slice (debug_backtrace (), 3);
            $caller = array_shift ($trace);
            return $caller['function'];
        }
        
        function assertTrue ($x) {         $this->report ($x == true, "was expecting true, got '$x'"); }
        function assertFalse ($x) {        $this->report ($x == false, "was expecting false, got '$x'"); }
        function assertNull ($x) {         $this->report ($x == null, "was expecting null, got '$x'"); }
        function assertNotNull ($x) {      $this->report ($x != null, "was not expecting null, got '$x'"); }
        function assertEqual ($x, $y) {    $this->report ($x == $y, "was expecting '$y', got '$x'"); }
        function assertNotEqual ($x, $y) { $this->report ($x != $y, "was expecting anything but '$x', but got it"); }
        function assertGreater ($x, $y) {  $this->report ($x > $y, "was expecting $x > $y"); }
        function assertLess ($x, $y) {     $this->report ($x < $y, "was expecting $y > $x"); }
        
        function assertIdentical ($x, $y) {
            // Fail if $x === $y is false
            $this->report ($x === $y, "The two objects are not identical");
        }
        
        function assertNotIdentical ($x, $y) {
            $this->report ($x !== $y, "The two objects are identical");
        }
        function assertIsA ($x, $t) {
            // Fail if $x is not the class or type $t
            $this->report (get_class ($x) == $t, "Object is not of type '$t'");
        }
    
        function assertReference ($x, $y) {
            // Fail unless $x and $y are the same variable
            $this->report ($x == $y && $x === $y, "The two variables do not reference the same object in memory");
        }
        
        function assertCopy ($x, $y) {
            // Fail if $x and $y are the same variable
            $this->report ($x == $y && $x !== $y, "The two objects are not copies");
        }
        
        function assertWantedPattern ($p, $x) {
            $this->report (preg_match ($p, $x) == 1, "Wanted pattern was not found in '$x'");
        }
        
        function assertNoUnwantedPattern ($p, $x) {
            $this->report (preg_match ($p, $x) == 0, "Unwanted pattern was found in '$x'");
        }
        
        private function report ($success, $message = '') {
            $methodname = $this->cyberpolice ();
        
            // push results to stack.
            $this->resultstack[] = array (
                'method' => $methodname, 
                'success' => $success, 
                'message' => $success ? '' : $message);
        }
    }
?>