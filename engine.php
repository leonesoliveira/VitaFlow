<?php
require_once 'init.php';

// AdiantiCoreApplication::setRouter(array('AdiantiRouteTranslator', 'translate'));


class TApplication extends AdiantiCoreApplication
{
    public static function run($debug = null)
    {
        new TSession;
        ApplicationTranslator::setLanguage( TSession::getValue('user_language'), true ); // multi-lang
        BuilderTranslator::setLanguage( TSession::getValue('user_language'), true ); // multi-lang
        
        if (! empty($_REQUEST['token_mobile']))
        {
            try
            {
                BuilderMobileService::initSessionFromToken($_REQUEST['token_mobile']);
            }
            catch (Exception $e)
            {
                new TMessage('erro', $e->getMessage());
                return;
            }
        }


        if ($_REQUEST)
        {
            $ini = AdiantiApplicationConfig::get();
            
            $class  = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
            $method  = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';
            $public = in_array($class, $ini['permission']['public_classes']);
            $public_mobile = in_array($class, array_keys($ini['user_public_pages']??[]));
            $debug  = is_null($debug)? $ini['general']['debug'] : $debug;

            if (TSession::getValue('logged')) // logged
            {
                $programs = (array) TSession::getValue('programs'); // programs with permission
                $programs = array_merge($programs, self::getDefaultPermissions());
                $programs_actions = TSession::getValue('programs_actions');
                
                if($public || $public_mobile)
                {
                    parent::run($debug);
                }
                elseif( (isset($programs_actions[$class][$method]) && $programs_actions[$class][$method] == false) || !isset($programs[$class]) )
                {
                    http_response_code(401);
                    new TMessage('error', _t('Permission denied'));
                }
                elseif( isset($programs[$class]) )
                {
                    parent::run($debug);
                }
            }
            else if ($class == 'LoginForm' || $public )
            {
                parent::run($debug);
            }
            else
            {
                http_response_code(401);
                new TMessage('error', _t('Permission denied'), new TAction(array('LoginForm','onLogout')) );
            }
        }
    }
    
    /**
     * Return default programs for logged users
     */
    public static function getDefaultPermissions()
    {
        return array('Adianti\Base\TStandardSeek' => TRUE,
                     'LoginForm' => TRUE,
                     'AdiantiMultiSearchService' => TRUE,
                     'AdiantiUploaderService' => TRUE,
                     'AdiantiAutocompleteService' => TRUE,
                     'EmptyPage' => TRUE,
                     'MessageList' => TRUE,
                     'SystemDocumentUploaderService' => TRUE,
                     'NotificationList' => TRUE,
                     'SearchBox' => TRUE,
                     'SearchInputBox' => TRUE,
                     'BuilderPageService' => TRUE,
                     'BuilderPageBatchUpdate' => TRUE,
                     'BuilderPermissionUpdate' => TRUE,
                     'BuilderConfigForm' => TRUE,
                     'BuilderConfigList' => TRUE,
                     'BuilderMenuUpdate' => TRUE,
                     'BuilderColumnDiffForm' => TRUE,
                     'BuilderConfirmCommandsDiffForm' => TRUE,
                     'BuilderCustomCssService' => TRUE,
                     'BuilderDatabaseDiffForm' => TRUE,
                     'BuilderTableDiffForm' => TRUE,
                     'BuilderViewsDiffForm' => TRUE,
                     'SystemFrameworkUpdate' => TRUE,
                     'SystemPageService' => TRUE,
                     'SystemPageBatchUpdate' => TRUE,
                     'SystemPermissionUpdate' => TRUE,
                     'SystemMenuUpdate' => TRUE,
                     'SystemChangeUnitForm' => TRUE,
                     'System2FAEmailForm' => TRUE,
                     'System2FAForm' => TRUE,
                     'System2FAGoogleForm' => TRUE,
                     'BuilderService' => TRUE
                    );
    } 
}

TApplication::run();
