<?php


/**
 * @group ppp_filters
 */
class Tests_Filters extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->_post_id = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'post', 'post_status' => 'publish' ) );
	}

	public function test_bitly_filters() {
		global $wp_filter;
		$this->assertarrayHasKey( 'ppp_apply_bitly', $wp_filter['ppp_apply_shortener-bitly'][10] );
	}

	public function test_facebook_filters() {
		global $wp_filter;
		$this->assertarrayHasKey( 'ppp_fb_register_service', $wp_filter['ppp_register_social_service'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_icon', $wp_filter['ppp_account_list_icon-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_avatar', $wp_filter['ppp_account_list_avatar-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_name', $wp_filter['ppp_account_list_name-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_actions', $wp_filter['ppp_account_list_actions-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_extras', $wp_filter['ppp_account_list_extras-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_query_vars', $wp_filter['query_vars'][10] );
		$this->assertarrayHasKey( 'ppp_fb_add_meta_tab', $wp_filter['ppp_metabox_tabs'][10] );
		$this->assertarrayHasKey( 'ppp_fb_register_metabox_content', $wp_filter['ppp_metabox_content'][10] );
	}

	public function test_text_tokens() {
		$tokens = ppp_set_default_text_tokens( array() );
		$this->assertEquals( 2, count( $tokens ) );

		$this->assertEquals( 'Test Post', ppp_replace_text_tokens( '{post_title}', array( 'post_id' => $this->_post_id ) ) );
		$this->assertEquals( 'Test Blog', ppp_replace_text_tokens( '{site_title}', array( 'post_id' => $this->_post_id ) ) );

		// Test the negative replacements when post_id is missing
		$this->assertEquals( '{post_title}', ppp_post_title_token( '{post_title}', array() ) );
	}

	public function test_unique_link_genreation() {
		$link = ppp_generate_unique_link( get_permalink( $this->_post_id ), $this->_post_id, 'sharedate_1_' . $this->_post_id . '_tw' );
		$this->assertEquals( 'http://example.org/?p=' . $this->_post_id . '&ppp=' . $this->_post_id . '-1', $link );
	}

	public function test_google_utm_links() {
		$link = ppp_generate_google_utm_link( get_permalink( $this->_post_id ), $this->_post_id, 'sharedate_1_' . $this->_post_id . '_tw' );
		$expected = 'http://example.org/?p=' . $this->_post_id . '&utm_source=Twitter&utm_medium=social&utm_term=test-post&utm_content=1&utm_campaign=PostPromoterPro';
		$this->assertEquals( $expected, $link );

		$link = ppp_generate_google_utm_link( get_permalink( $this->_post_id ), $this->_post_id, 'sharedate_1_' . $this->_post_id . '_li' );
		$expected = 'http://example.org/?p=' . $this->_post_id . '&utm_source=LinkedIn&utm_medium=social&utm_term=test-post&utm_content=1&utm_campaign=PostPromoterPro';
		$this->assertEquals( $expected, $link );

		$link = ppp_generate_google_utm_link( get_permalink( $this->_post_id ), $this->_post_id, 'sharedate_1_' . $this->_post_id . '_fb' );
		$expected = 'http://example.org/?p=' . $this->_post_id . '&utm_source=Facebook&utm_medium=social&utm_term=test-post&utm_content=1&utm_campaign=PostPromoterPro';
		$this->assertEquals( $expected, $link );
	}

}
