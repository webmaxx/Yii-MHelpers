Closed
======

Yii helpers.

1. Copy all files to protected/components/

2. In config:
	...
	'import' => array(
		...
		'application.components.*',
		...
	),
	...

3. Use variants:
	MHelper::get()->String->toLower('Text');
	OR
	MHelper::get('String')->toLower('Text');
	OR
	MHelper::String()->toLower('Text');	// PHP 5.3 only


Helpers list.

String
	
	Methods:
		toUpper
		toLower
		substr
		strrchr
		len
		trimSlashes
		stripSlashes
		stripQuotes
		quotesToEntities
		reduceDoubleSlashes
		reduceMultiples
		incrementString
		alternator
		repeater
		wordwrap
		truncate
		randomString
		toTranslit
		fromTranslit

Array
	
	Methods:
		element
		randomElement
		elements

Date
	
	Methods:
		timeAgo
		daysInMonth

Directory

	Methods:
		map
		clear
		delete

File
	
	Methods:
		isFile
		isReadable
		read
		write
		delete
		filenames
		filesInfo
		fileInfo
		pathInfo
		size
		sizeFormat
		send
		download
		getMimeType
		getMimeTypeByExtension

