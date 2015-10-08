<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 25-9-15 - 13:42
 */

namespace CWDatabase\Helper;


class Message
{
	// \m/ (^-^) \m/
	const ERROR_MESSAGE_XML_PARSER    = "ErrorMessage error: Can't parse the error messages xml file.";
	const ERROR_MESSAGE_UNDEFINED_KEY = "ErrorMessage error: The given key is not found in the error messages xml file.";

	const XML_FILE_PATH = WEBSERVER_ROOT_PATH . "CWDatabase/Config/messages.xml";

	const PLACEHOLDER_START = "{{";
	const PLACEHOLDER_END   = "}}";

	const DEFAULT_MESSAGE_PLACEHOLDER = self::PLACEHOLDER_START . "var" . self::PLACEHOLDER_END;

	/**
	 * This method will return an message based on the key. the messages are defined in
	 * {project_root}/Config/messages.xml. The key consist out of segments separated by dots that represent an array.
	 *
	 * @param $key
	 *
	 * @return array
	 */
	public static function getMessage( $key, $placeholders = [ ] )
	{
		$messages = self::parseXmlFile();

		$message = Arr::get( $messages, $key );

		if( !$message )
		{
			trigger_error( self::ERROR_MESSAGE_UNDEFINED_KEY, E_USER_ERROR );

			return false;
		}

		// If there are placeholders replace them with the values.
		if( count( $placeholders ) )
		{
			$message = self::insertPlaceholders( $message, $placeholders );
		}

		return $message;
	}

	/**
	 * This method replaces placeholders in messages with the value.
	 *
	 * You can insert placeholders as an associate like [ "placeholder1" => "value1" ] then it will search in the
	 * message for {{placeholder1}} and replace it by value1.
	 * Or you can insert placeholders by count like [ "val1", "val2" ] then it will search for
	 * self::DEFAULT_MESSAGE_PLACEHOLDER and replace it with the value
	 *
	 * @param $string
	 * @param $string
	 * @param $placeholders
	 *
	 * @return mixed
	 */
	private static function insertPlaceholders( $string, $placeholders )
	{
		$message = $string;

		if( Arr::isAssoc( $placeholders ) )
		{
			foreach( $placeholders as $key => $value )
			{
				$key = self::PLACEHOLDER_START . $key . self::PLACEHOLDER_END;

				$message = str_replace( $key, $value, $message );
			}
		}
		else
		{
			$message = Str::replaceArray( $string, self::DEFAULT_MESSAGE_PLACEHOLDER, $placeholders );
		}

		return $message;
	}

	/**
	 * This will return an array with all the error messages.
	 * @return array
	 */
	private static function parseXmlFile()
	{
		$errorMessagesFile = simplexml_load_file( self::XML_FILE_PATH );

		if( $errorMessagesFile )
		{
			$errorMessages = Arr::objectToArray( $errorMessagesFile );

			return $errorMessages;
		}
		else
		{
			trigger_error( self::ERROR_MESSAGE_XML_PARSER, E_USER_ERROR );

			return false;
		}
	}


}