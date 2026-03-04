<?php
/*
Name: 			Contact Form - Multi-Layer Anti-Spam
Written by: 	Claude Code (Modified from Porto Template)
Theme Version:	8.0.0
Anti-Spam:		Honeypot + Time Validation + JS Challenge Token + Content Filter
*/

namespace PortoContactForm;

ini_set('allow_url_fopen', true);

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php-mailer/src/PHPMailer.php';
require 'php-mailer/src/SMTP.php';
require 'php-mailer/src/Exception.php';

// Anti-Spam Validation
$spamDetected = false;
$spamReason = '';

// 1. Honeypot Check - "website" field must be empty
if (isset($_POST['website']) && !empty($_POST['website'])) {
	$spamDetected = true;
	$spamReason = 'Honeypot filled';

	// Log spam attempt (optional - für Debugging)
	error_log('Spam detected: Honeypot filled - IP: ' . $_SERVER['REMOTE_ADDR']);
}

// 2. Time Validation - Form must be filled for at least 3 seconds
if (!$spamDetected && isset($_POST['form_timestamp'])) {
	$timestamp = intval($_POST['form_timestamp']);
	$currentTime = round(microtime(true) * 1000); // milliseconds
	$timeDiff = $currentTime - $timestamp;

	// Minimum 3 seconds (3000 milliseconds)
	if ($timeDiff < 3000) {
		$spamDetected = true;
		$spamReason = 'Form submitted too fast (' . $timeDiff . 'ms)';

		error_log('Spam detected: Too fast - IP: ' . $_SERVER['REMOTE_ADDR'] . ' - Time: ' . $timeDiff . 'ms');
	}

	// Maximum 1 hour (prevent token reuse)
	if ($timeDiff > 3600000) {
		$spamDetected = true;
		$spamReason = 'Form token expired';
	}
}

// 3. Check if timestamp exists
if (!$spamDetected && !isset($_POST['form_timestamp'])) {
	$spamDetected = true;
	$spamReason = 'Missing timestamp';
}

// 4. Challenge Token Validation (JS-generated)
if (!$spamDetected) {
	if (!isset($_POST['challenge_token']) || empty($_POST['challenge_token'])) {
		$spamDetected = true;
		$spamReason = 'Missing challenge token';
		error_log('Spam detected: No challenge token - IP: ' . $_SERVER['REMOTE_ADDR']);
	} else {
		$decoded = base64_decode($_POST['challenge_token'], true);
		if ($decoded === false || strpos($decoded, ':') === false) {
			$spamDetected = true;
			$spamReason = 'Invalid challenge token format';
			error_log('Spam detected: Bad token format - IP: ' . $_SERVER['REMOTE_ADDR']);
		} else {
			$parts = explode(':', $decoded, 2);
			$tokenTs = intval($parts[0]);
			$tokenResult = intval($parts[1]);
			$expectedResult = ($tokenTs % 97) * 3;

			if ($tokenResult !== $expectedResult) {
				$spamDetected = true;
				$spamReason = 'Challenge token mismatch';
				error_log('Spam detected: Token mismatch - IP: ' . $_SERVER['REMOTE_ADDR']);
			}
			// Timestamp in token must match form_timestamp
			if (!$spamDetected && isset($_POST['form_timestamp']) && $tokenTs !== intval($_POST['form_timestamp'])) {
				$spamDetected = true;
				$spamReason = 'Token timestamp mismatch';
				error_log('Spam detected: Token/timestamp mismatch - IP: ' . $_SERVER['REMOTE_ADDR']);
			}
		}
	}
}

// 5. URL Detection in text fields
if (!$spamDetected) {
	$textFields = array(
		isset($_POST['name']) ? $_POST['name'] : '',
		isset($_POST['betreff']) ? $_POST['betreff'] : '',
		isset($_POST['message']) ? $_POST['message'] : ''
	);
	$urlPattern = '/(https?:\/\/|www\.|:\/\/|\.com\/|\.ru\/|\.cn\/|\.tk\/|\.xyz\/|\.top\/|\.click\/|\.link\/|\[url|<a\s+href)/i';
	foreach ($textFields as $field) {
		if (preg_match($urlPattern, $field)) {
			$spamDetected = true;
			$spamReason = 'URL in form fields';
			error_log('Spam detected: URL found - IP: ' . $_SERVER['REMOTE_ADDR']);
			break;
		}
	}
}

// 6. Spam pattern detection
if (!$spamDetected) {
	$allText = (isset($_POST['name']) ? $_POST['name'] : '') . ' '
		. (isset($_POST['betreff']) ? $_POST['betreff'] : '') . ' '
		. (isset($_POST['message']) ? $_POST['message'] : '');

	// Non-Latin character sets (Cyrillic, CJK, Arabic)
	if (preg_match('/[\x{0400}-\x{04FF}\x{4E00}-\x{9FFF}\x{3040}-\x{30FF}\x{0600}-\x{06FF}]/u', $allText)) {
		$spamDetected = true;
		$spamReason = 'Non-Latin characters detected';
		error_log('Spam detected: Non-Latin chars - IP: ' . $_SERVER['REMOTE_ADDR']);
	}

	// Spam phrases (case-insensitive)
	if (!$spamDetected) {
		$spamPhrases = array(
			'seo', 'marketing', 'casino', 'crypto', 'bitcoin', 'viagra', 'cialis',
			'cbd', 'loan', 'investment', 'click here', 'buy now', 'free offer',
			'web design', 'website traffic', 'backlink', 'rank your', 'first page',
			'google ranking', 'followers', 'instagram', 'tiktok', 'social media',
			'earn money', 'make money', 'work from home', 'passive income',
			'diet pill', 'weight loss', 'enlargement', 'pharmacy'
		);
		$lowerText = mb_strtolower($allText, 'UTF-8');
		foreach ($spamPhrases as $phrase) {
			if (strpos($lowerText, $phrase) !== false) {
				$spamDetected = true;
				$spamReason = 'Spam phrase: ' . $phrase;
				error_log('Spam detected: Phrase "' . $phrase . '" - IP: ' . $_SERVER['REMOTE_ADDR']);
				break;
			}
		}
	}

	// Excessive caps (>60% uppercase in messages longer than 20 chars)
	if (!$spamDetected) {
		$msg = isset($_POST['message']) ? $_POST['message'] : '';
		if (mb_strlen($msg) > 20) {
			$upperCount = preg_match_all('/[A-ZÄÖÜ]/u', $msg);
			$letterCount = preg_match_all('/[a-zA-ZäöüÄÖÜß]/u', $msg);
			if ($letterCount > 0 && ($upperCount / $letterCount) > 0.6) {
				$spamDetected = true;
				$spamReason = 'Excessive caps';
				error_log('Spam detected: Excessive caps - IP: ' . $_SERVER['REMOTE_ADDR']);
			}
		}
	}

	// Name equals subject (bot pattern)
	if (!$spamDetected && isset($_POST['name']) && isset($_POST['betreff'])) {
		if (!empty($_POST['name']) && $_POST['name'] === $_POST['betreff']) {
			$spamDetected = true;
			$spamReason = 'Name equals subject';
			error_log('Spam detected: Name==Subject - IP: ' . $_SERVER['REMOTE_ADDR']);
		}
	}
}

// 7. Disposable email domain check
if (!$spamDetected && isset($_POST['email'])) {
	$emailDomain = strtolower(substr(strrchr($_POST['email'], '@'), 1));
	$disposableDomains = array(
		'mailinator.com', 'guerrillamail.com', 'tempmail.com', 'yopmail.com',
		'throwaway.email', 'sharklasers.com', 'guerrillamailblock.com', 'grr.la',
		'discard.email', 'temp-mail.org', 'fakeinbox.com', 'maildrop.cc',
		'trashmail.com', 'tempail.com', 'mohmal.com', 'getnada.com',
		'emailondeck.com', 'mintemail.com', 'trashmail.me', 'harakirimail.com',
		'mailnesia.com', 'tempr.email', 'dispostable.com', 'mailcatch.com',
		'10minutemail.com', 'guerrillamail.info', 'guerrillamail.net',
		'guerrillamail.org', 'guerrillamail.de', 'spam4.me', 'trashmail.net',
		'wegwerfmail.de', 'wegwerfmail.net', 'byom.de', 'trash-mail.com'
	);
	if (in_array($emailDomain, $disposableDomains)) {
		$spamDetected = true;
		$spamReason = 'Disposable email: ' . $emailDomain;
		error_log('Spam detected: Disposable email ' . $emailDomain . ' - IP: ' . $_SERVER['REMOTE_ADDR']);
	}
}

// If spam detected, return error
if ($spamDetected) {
	$arrResult = array(
		'response' => 'error',
		'errorMessage' => 'Ihre Nachricht wurde als Spam erkannt. Bitte versuchen Sie es erneut oder kontaktieren Sie uns telefonisch.'
	);
	echo json_encode($arrResult);
	exit;
}

// Proceed with sending email if no spam detected
// Step 1 - Email address
$email = 'hallo@bessersehen.la';

// If the e-mail is not working, change the debug option to 2 | $debug = 2;
$debug = 0;

// Subject
$subject = (isset($_POST['betreff']) && !empty($_POST['betreff']))
	? $_POST['betreff']
	: 'Besser Sehen Landshut - Kontaktformular';

$message = '';

foreach($_POST as $label => $value) {
	// Skip anti-spam fields
	if (in_array($label, array('website', 'form_timestamp', 'challenge_token', 'g-recaptcha-response'))) {
		continue;
	}

	$label = ucwords($label);

	// German field names
	$fieldTranslations = array(
		'Name' => 'Name',
		'Email' => 'E-Mail',
		'Betreff' => 'Betreff',
		'Message' => 'Nachricht'
	);

	if (isset($fieldTranslations[$label])) {
		$label = $fieldTranslations[$label];
	}

	// Checkboxes
	if (is_array($value)) {
		$value = implode(', ', $value);
	}

	$message .= $label . ": " . htmlspecialchars($value, ENT_QUOTES) . "<br>\n";
}

// Add metadata
$message .= "<br><br>---<br>";
$message .= "Gesendet am: " . date('d.m.Y H:i:s') . "<br>";
$message .= "IP-Adresse: " . $_SERVER['REMOTE_ADDR'] . "<br>";

$mail = new PHPMailer(true);

try {

	$mail->SMTPDebug = $debug;

	// Optional SMTP configuration (uncomment if needed)
	//$mail->IsSMTP();
	//$mail->Host = 'mail.yourserver.com';
	//$mail->SMTPAuth = true;
	//$mail->Username = 'user@example.com';
	//$mail->Password = 'secret';
	//$mail->SMTPSecure = 'tls';
	//$mail->Port = 587;

	$mail->AddAddress($email);

	// From - Name
	$fromName = (isset($_POST['name'])) ? $_POST['name'] : 'Website User';
	$mail->SetFrom($email, $fromName);

	// Reply To
	if (isset($_POST['email'])) {
		$mail->AddReplyTo($_POST['email'], $fromName);
	}

	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';

	$mail->Subject = $subject;
	$mail->Body = $message;

	$mail->Send();
	$arrResult = array('response' => 'success');

} catch (Exception $e) {
	$arrResult = array('response' => 'error', 'errorMessage' => $e->errorMessage());
} catch (\Exception $e) {
	$arrResult = array('response' => 'error', 'errorMessage' => $e->getMessage());
}

if ($debug == 0) {
	echo json_encode($arrResult);
}
