# Testando post 2
- Luis Capelo
- luiscape
- 2013/12/27
- The System
- published

When we started DAP almost a year ago we created the Common Humanitarian Dataset. The CHD, as we call it, intends to create a collection of indicators that are both sustainably collected by authoritative institutions and that are already used by humanitarian actors in their analysis. For example, the CHD has nutrition indicators from FAO, poverty indicators from the World Bank, and basic health indicators from the World Health Organization.  

In other words, DAP uses an indicator based approach to populate its repository. The idea is that the CHD will be community-driven throughout time, having a community of humanitarian analysis questioning old indicators, adding new ones and so forth. It provides a common-ground for conversation, but still leaving the system open to be populated by any other data that is considered relevant. 

The full list of indicators currently available in the CHD can be found here: http://goo.gl/Id4jiQ 

(Please note that the list is a refined draft – there is a lot of work to be done there).

Based on the CHD we partnered with ScraperWiki, a company from the UK specialized in scraping data from the web. Out friends 
Ian Hopkinson and Dave Mckee, both fantastic data scientists at ScraperWiki, build scrapers to collect data from a number of indicators from the CHD. In short, the scrapers are scripts that go to a certain website and “extract” the data out of those websites. They, then, put the data into a single database using unique identifiers – in our case countries’ ISO3c codes. 

Thanks to the work our friends from ScraperWiki have done we now have collected a good amount of authoritative data commonly used to understand countries that have a humanitarian crisis. Here is on Ian’s words: 

In total we’ve collected over 300,000 data points for 240 different regions (mostly countries – plus dependent territories such as Martinique, Aruba, Hong Kong, French Guiana and so forth), covering a period of 60 years with 233 different indicators. 