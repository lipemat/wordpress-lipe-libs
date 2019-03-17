<?php

namespace Lipe\Lib\Traits;

/**
 * Support simple memoization for class methods which respond
 * with different caches based on the arguments provided.
 *
 * @example public function heavy( $text ) {
 *              return $this->memoize( function ( $text ) {
 *                   echo 'called' . "\n";
 *                    return $text;
 *                }, __METHOD__, $text );
 *            }
 *          $test->heavy( 'as hell'. "\n" ); //called
 *          $test->heavy( 'as hell x2' . "\n"); //called
 *          $test->heavy( 'as hell x2' . "\n"); //Not called
 *          $test->heavy( 'as hell X3'. "\n" ); // called
 *
 * @author  Mat Lipe
 * @since   2.6.0
 *
 */
trait Memoize {
	protected $memoize_cache = [];


	/**
	 * Pass me a callback, a method identifier, and some optional arguments
	 * and I will return same same result every time the arguments are the same.
	 *
	 * If the arguments change, I will return a result matching the change.
	 * I will only call the callback one time for the same set of arguments.
	 *
	 * @example
	 *
	 * @since 2.6.0
	 *
	 * @param callable $fn
	 * @param string   $identifier - Something unique to identify the the method being used
	 *                             so we can determine the difference in the cache.
	 *                             `__METHOD__` works nicely here.
	 * @param mixed    ...$args    - Arguments will be passed to the callback as well as determine
	 *                             if we can reuse a result.
	 *
	 * @return mixed
	 */
	public function memoize( callable $fn, string $identifier, ...$args ) {
		$key = md5( serialize( [ $args, $identifier ] ) );
		if ( ! array_key_exists( $key, $this->memoize_cache ) ) {
			$this->memoize_cache[ $key ] = call_user_func_array( $fn, $args );
		}

		return $this->memoize_cache[ $key ];
	}
}