# README #

Cakephp Wrapper for the [Pardot](http://www.pardot.com/) API.  Currently this wrapper only contains a method for creating prospects in pardot but can be used as a starting point for a customized wrapper.  

### Setup steps ###

* Install wrapper as a component in app/Controller/Component
* Include your user key

```
#!php

//replace with pardot api user key
    const user_key = 'xxxxxxxxxxxx';
```


### Usage ###
* Load pardot wrapper into controller
```
#!php
public $components = array('Pardot');

```

* Assemble data array to send to pardot api and call wrapper method 
```
#!php

// Create new pardot prospect from trial
$prospect_data = array(
  'first_name'=>$this->request->data['Store']['first_name'],
  'last_name'=>$this->request->data['Store']['last_name'],
  'address_one'=>$this->request->data['Store']['address1'],
  'city'=>$this->request->data['Store']['city'],
  'state'=>$this->states_array[$this->request->data['Store']['state']],
  'zip'=>$this->request->data['Store']['zip'],
  'phone'=>$this->request->data['Store']['phone']
);
  $pardot_prospect = $this->Pardot->create_prospect($store['Store']['email'], $prospect_data);
```


### Contact ###

* Repo owner: Dustin Weaver
* dweaver@dustinweaver.com