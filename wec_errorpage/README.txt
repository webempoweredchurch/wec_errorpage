Purpose:
This extension allows TYPO3 installations that support more than one domain to define page not found (404) and page unavailable (503, TYPO3 4.2+ only) error handling on a per domain basis

How?
WEC Error Page extends TYPO3 domain records to include an input text field with a link wizard that can be used to select an external URL, an internal TYPO3 page, or a static file.
Furthermore, the user can directly write into the input field using the very same options that are supported by the Install Tool's pageNotFound_handling directive.

Using Typo3 Link Processing:
In order to use most of the link wizard's features we need to process the input with typolink. This adds some overhead to our processing and results in a little slower page load. Disabling this setting in the Extension Manager also disables internal and external pages in the link wizard, but speeds up the page rendering. It's a trade off between user friendliness and performance.

We recommend turning it off if the user is familiar with the Install Tool option and is able to write the configuration into the input field directly without using the wizard. 