<?php
class gamp {
  
  const GA_URL = 'https://ssl.google-analytics.com/collect';
  
  public $debug = false;
  public $log = array();
  
	public $data = array(
    'v' => 1
  );
  
  // init with UA; set clientId and hostname
  public function __construct( $ua = '', $host = '', $data = array() ) {
    
    $this->data = array_merge( $this->data, array(
    
      'tid' => $ua,
      'cid' => $this->cookie( 'clientId' ),
      'uip' => $_SERVER['REMOTE_ADDR'],
      'dh' => ( $host != '' ? $host : $_SERVER['HTTP_HOST'] )
    
    ), $data );
    
	}
  
  // parse the GA Cookie
  public function cookie( $component = '' ) {
    
    if ( ! isset($_COOKIE['_ga']) ) return false;

    list($version, $domainDepth, $cid1, $cid2) = explode('.', $_COOKIE['_ga'], 4);
    
    $components = array(
      'version' => $version, 
      'domainDepth' => $domainDepth, 
      'clientId' => $cid1 . '.' . $cid2 
    );
    
    if ( $component == '' ) return $components;
    
    return $components[ $component ];
    
  }

  // send pageview
  public function pageview( $page = '', $title = '' ) {
    if ( $page == '' ) {
      $page = $_SERVER['REQUEST_URI'];
    }
    if ( $title == '' ) {
      $title = $page;
    }
    return $this->hit( array(
      't' => 'pageview',
      'dp' => $page,
      'dt' => $title
    ) );
  }

  // send event
  public function event( $category = '', $action = '', $label = '' ) {
    return $this->hit( array(
      't' => 'event',
      'ec' => $category,
      'ea' => $action,
      'el' => $label
    ) );
  }
  
  // send data to GA
  public function hit( $data ) {
    
    // don't track anon events
    if ( $data['t'] == 'event' ) {
      if ( $this->debug ) $this->log[] = 'Ignoring anonymous event, no CID set';
      if ( ! isset( $this->data['cid'] ) || ! $this->data['cid'] ) return false;
    }
    
    $endpoint = $this->debug ? 
      'https://ssl.google-analytics.com/debug/collect' : 
      self::GA_URL;
    
    $data = array_merge( $this->data, $data );
    if ( $this->debug ) $this->log[] = 'Sending Hit: ' . print_r( $data, true );
    
    $ch = curl_init( $endpoint );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    
    $response = '';
    if( ( $response = curl_exec($ch) ) === false ) {
      if ( $this->debug ) $this->log[] = 'Curl error: ' . curl_error($ch);
      return false;
    }
    
    if ( $this->debug ) $this->log[] = 'Curl response: ' . print_r( $response, true );
    
    curl_close($ch);
    return true;
  }
  
}