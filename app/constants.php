<?php

if (!defined('REGEX_PHONE')) define('REGEX_PHONE', '/^[(]{0,1}[0-9]{3}[)]{0,1}[-\s\.]{0,1}[0-9]{3}[-\s\.]{0,1}[0-9]{4}$/');
if (!defined('REGEX_ADDRESS')) define('REGEX_ADDRESS', '/^[a-zA-Z0-9\s#,.\'-]+$/');
if (!defined('REGEX_GEO')) define('REGEX_GEO', '/^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/');
if (!defined('REGEX_ZIP')) define('REGEX_ZIP', '/(^\d{5}$)/');
if (!defined('REGEX_BUSINESS_NAME') ) define('REGEX_BUSINESS_NAME', '/^[0-9A-Za-z%\'-]+(?:\s[0-9A-Za-z\'-\,\.]+)*$/');
if (!defined('REGEX_NAME')) define('REGEX_NAME', '/^[a-zA-Z%\s\.\-,]+$/');
if (!defined('REGEX_SSN')) define('REGEX_SSN', '/^\\d{3}-\\d{2}-\\d{4}$/');
if (!defined('REGEX_DLN')) define('REGEX_DLN', '/^[0-9a-zA-Z]{4,9}$/');
if (!defined('REGEX_MD5')) define('REGEX_MD5', '/^[a-f0-9]{32}$/');

if (!defined('RTO_COMPANY_ID')) define('RTO_COMPANY_ID', 'rto_company_id');
if (!defined('MANUFACTURER_COMPANY_ID')) define('MANUFACTURER_COMPANY_ID', 'manufacturer_company_id');
if (!defined('COMPANY_ID')) define('COMPANY_ID', 'company_id');