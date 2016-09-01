<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Strip styles
	|--------------------------------------------------------------------------
	|
	| Settings this to false prevents the inliner from removing the style
	| definitions that have been inlined.
	|
	| Notice that media query styles are not inlined, and hence never
	| stripped.
	|
	*/

	'strip-styles' => true,

	/*
	|--------------------------------------------------------------------------
	| Remove classes
	|--------------------------------------------------------------------------
	|
	| Settings this to false disables the removal of class attributes from
	| your html elements (do not enable this if you use media queries)
	|
	*/

	'strip-classes' => true,

];
