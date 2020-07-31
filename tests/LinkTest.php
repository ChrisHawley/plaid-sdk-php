<?php

namespace TomorrowIdeas\Plaid\Tests;

/**
 * @covers TomorrowIdeas\Plaid\Plaid
 */
class LinkTest extends TestCase
{
	public function test_get_link_token()
	{
		$response = $this->getPlaidClient()->createLinkToken(
			"Test Client Name",
			"12345",
			"nl",
			["NL", "IE"],
			["auth", "transactions"],
			"https://www.plaid.com",
			"default",
			"https://www.plaid.com/webhook",
			"access_token",
			["credit" => ["account_subtypes" => ["credit card"]]],
			"Test Package Name",
			["payment_id" => "67890"]);

		$this->assertEquals("POST", $response->method);
		$this->assertEquals("2019-05-29", $response->version);
		$this->assertEquals("application/json", $response->content);
		$this->assertEquals("/link/token/create", $response->path);
		$this->assertEquals("client_id", $response->params->client_id);
		$this->assertEquals("secret", $response->params->secret);
		$this->assertEquals("12345", $response->params->user->client_user_id);
		$this->assertEquals("nl", $response->params->language);
		$this->assertContains("NL", $response->params->country_codes);
		$this->assertContains("IE", $response->params->country_codes);
		$this->assertContains("auth", $response->params->products);
		$this->assertContains("transactions", $response->params->products);
		$this->assertEquals("https://www.plaid.com", $response->params->redirect_uri);
		$this->assertEquals("default", $response->params->link_customization_name);
		$this->assertEquals("https://www.plaid.com/webhook", $response->params->webhook);
		$this->assertEquals("access_token", $response->params->access_token);
		$this->assertObjectHasAttribute("credit", $response->params->account_filters);
		$this->assertObjectHasAttribute("account_subtypes", $response->params->account_filters->credit);
		$this->assertContains("credit card", $response->params->account_filters->credit->account_subtypes);
		$this->assertEquals("Test Package Name", $response->params->android_package_name);
		$this->assertObjectHasAttribute("payment_id", $response->params->payment_initiation);
		$this->assertEquals("67890", $response->params->payment_initiation->payment_id);
	}
}
