<?php

$shopify_api_key = '#api_key';
$shopify_api_password = '#api_password';
$shop_name = '#shop_name';

$graphql_url = 'https://'.$shopify_api_key.':'.$shopify_api_password.'@'.$shop_name.'/admin/api/2020-07/graphql.json';

$query = <<< 'JSON'
query {
  orders(first: 2, reverse:true, query:"NOT exported AND status:open AND (financial_status:paid OR financial_status:pending)"){
    edges {
      node {
        id,
        name,
        createdAt,
        tags,
        displayFinancialStatus,
        shippingLine{code},
        transactions{gateway},    
        lineItems (first:10){
            edges{
                node{
                    sku,
                    quantity,
                    originalTotalSet{
                        presentmentMoney{amount},
                    }
                }
            }
        },
        discountCode,
        customer{
            id,
            firstName,
            lastName,
            email,
            defaultAddress{
                address1,address2,zip,city,country,countryCodeV2
            }
        },
        shippingAddress{
          firstName,
          lastName,
          company,  
          address1,
          address2,
          phone,
          zip,
          city,
          province,
          countryCodeV2
        },

      }
    }
  }
}

JSON;

$post_data = array();
$post_data['query'] = $query;

$curl_init = curl_init();
curl_setopt($curl_init, CURLOPT_URL, $graphql_url);
curl_setopt($curl_init, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_init, CURLOPT_VERBOSE, 0);
curl_setopt($curl_init, CURLOPT_HEADER, false);
curl_setopt($curl_init, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl_init, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec ($curl_init);
$decode_response = json_decode($response, true);
$httpcode = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
curl_close ($curl_init);

print_r($decode_response);
?>
