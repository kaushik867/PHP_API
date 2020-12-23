
<?php 

use DI\Container;

return function(Container $container){
    $container->set('settings',function(){

        return [
            'displayErrorsDetails'=>true,
            'logErrorsDetails'=>true,
            'logErrors'=>true,
        ];
    });
};