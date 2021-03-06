<?php
/**
 * @link https://github.com/bitrix-expert/monolog-adapter
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog;

use Bitrix\Main\ArgumentNullException;
use Monolog\Logger;
use Monolog\Registry;

/**
 * Logger of exceptions. Writes uncaught exceptions to the log.
 * 
 * Register the application logger in the `.settings.php` and add his to `exception_handling`:
 * ```php
 * return array(
 *      'monolog' => array(
 *          'value' => array(
 *              'loggers' => array(
 *                  'app' => array(
 *                      // Logger configs
 *                  )
 *              )
 *          ),
 *          'readonly' => false,
 *      ),
 *      'exception_handling' => array(
 *          'value' => array(
 *              'log' => array(
 *                  'class_name' => '\Bex\Monolog\ExceptionHandlerLog',
 *                  'settings' => array(
 *                      'logger' => 'app',
 *                  ),
 *              ),
 *          ),
 *          'readonly' => false,
 *      ),
 * );
 * ```
 * 
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * {@inheritdoc}
     */
    public function initialize(array $options)
    {
        if (!isset($options['logger']))
        {
            throw new ArgumentNullException('logger');
        }
        
        $this->logger = Registry::getInstance($options['logger']);
    }

    /**
     * {@inheritdoc}
     */
    public function write(\Exception $exception, $logType)
    {
        $this->logger->emergency($exception->getMessage(), array(
            'exception' => $exception->getTrace(),
            'logType' => $logType
        ));
    }
}