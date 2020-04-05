
# Google Cloud Platform Setup

We chosen to put the project in GCP because they handle security, backup, auto scaling and many
things that are expensive to do by ourselves.

We need several things in order to make the project work and maintainable:

- a VPC network, in order to build a private network for our application, without any public IP

- a Cloud SQL, that will store data of the application

- a Bastion, in order to connect to the Cloud SQL from home (to access or migrate it)

- an App Engine, which will expose the app outside

Do not mess up with any of the fields, **especially the "locations" (europe-west1 here)** as Google do not
allow to update an app engine instance once it has been created. Yeah, you can have many Compute Engine
instances, many Cloud SQL instances, but only one f*cking App Engine instance that is not modifiable.

## Make sure the application is working locally

You will find some help in the [CONTRIBUTING](../../CONTRIBUTING.md) and in the [development setup](00-development.md).

## Creating a new Google Cloud Platform project

1. Go to https://console.cloud.google.com

2. Click on "Select a project"
<br/>![](01/01.png)

3. Click on "New Project"
<br/>![](01/02.png)

4. Choose a name for the project and click "Create"
<br/>![](01/03.png)

5. Select the new project in the "Select a project" menu

## Creating a VPC network

1. Click on the Menu, and then "VCP Network" in the Networking part.
<br/>![](01/04.png)

2. Click "Create VPC Network"
<br/>![](01/05.png)

3. Configure your VPC with the following parameters:
<br/>![](01/06.png)
<br/>![](01/07.png)
<br/>![](01/08.png)

4. Open your VPC network in order to set firewall rules:
<br/>![](01/09.png)

5. Click on "Firewall rules"
<br/>![](01/10.png)

6. Click "Add firewall rule"
<br/>![](01/11.png)

7. Add the "ingress" firewall rule
<br/>![](01/12.png)
<br/>![](01/13.png)

8. Click "Add firewall rule"
<br/>![](01/14.png)

9. Add the "egress" firewall rule
<br/>![](01/15.png)
<br/>![](01/16.png)

10. Go to https://console.cloud.google.com/marketplace/details/google/vpcaccess.googleapis.com?project=covid-fight-redcall&folder=&organizationId= and enable the Serverless VPC Access API

11. Go to "Serverless VPC Connector" and "Create Connector" 
<br/>![](01/56.png)

12. Fill up the form, keep the VPC connector name, it will be used in configuration
<br/>![](01/57.png)

## Creating a Bastion

1. Click on the menu, then "Compute Engine" in Compute category, then "VM instances".
<br/>![](01/17.png)

2. Click "Create"
<br/>![](01/18.png)

3. Set-up the compute engine as follow, taking care of the red parts, and click on "Management, Security, Disks...". 
<br/>![](01/19.png)

4. On Management tab, make sure "On host maintenance" is set to "Migrate VM Instance" 
<br/>![](01/20.png)

5. On Security tab, check "Turn on Secure Boot"
<br/>![](01/21.png)

6. On Network tab, select the VPC created earlier and click "Done"
<br/>![](01/22.png)

## Creating a Cloud SQL instance

1. In the Menu, choose "SQL" under "Storage" category.
<br/>![](01/23.png)

2. Click "Create instance"
<br/>![](01/24.png)

3. Click "MySQL"
<br/>![](01/25.png)

4. Enter your db information and press Create. **Care about the "Region"**.
<br/>![](01/26.png)

5. Click on your Cloud SQL instance
<br/>![](01/27.png)

6. Go to "Connections" tab
<br/>![](01/28.png)

7. Choose "Private IP" (accept when you will be prompted to enable Network API) and select your network, press Save.
<br/>![](01/29.png)

8. Go to the "Databases" tab
<br/>![](01/30.png)

9. Click "Create database"
<br/>![](01/31.png)

10. Add your database information. 
<br/>![](01/32.png)

11. Click on "Users" tab
<br/>![](01/33.png)

12. Click on "Add user" 
<br/>![](01/34.png)

13. Create your new user. Keep those credentials safe, you'll need them soon.
<br/>![](01/35.png)

14. Go back to "Overview"
<br/>![](01/36.png)

15. Look up for your private IP address and set it in your dotenv (`deploy/<your project>/dotenv`), in the "DATABASE_HOST" variable.
<br/>![](01/37.png)

## Creating an App Engine instance

1. Go to the Menu, then "App Engine" in the "Compute" category, then "Dashboard"
<br/>![](01/38.png)

2. Click "Create Application"
<br/>![](01/39.png)

3. Choose the same zone as you chosen in your VPC network
<br/>![](01/40.png)

4. Select PHP and Standard
<br/>![](01/41.png)

5. Click "I'll do it later" at the bottom left
<br/>![](01/42.png)

## Creating a Google Service Account

In order to write logs, our application requires a google service account.

1. Go to the Menu, then "IAM & Admin", then "Service Accounts"
<br/>![](01/43.png)

2. Click "Create Service account"
<br/>![](01/44.png)

3. Enter your service account details (they don't really matter)
<br/>![](01/45.png)

4. Add the "Logs Writer" permission
<br/>![](01/46.png)

5. Click "Create Key"
<br/>![](01/47.png)

6. Choose the JSON format and press "Create"
<br/>![](01/48.png)

7. You will download a JSON file. Place it in `deploy/<your project>/google-service-account.json`
<br/>![](01/49.png)

## Creating a Custom Domain

In order to reach out the application from a custom domain name, we should configure it.

1. Go to the Menu, then "App Engine" in "Compute" category, then "Settings"
<br/>![](01/50.png)

2. Click on "Custom domain" tab
<br/>![](01/51.png)

3. Click "Add a custom domain" and follow the verification process
<br/>![](01/52.png)

4. Remove the extra "www." if you are using a subdomain and press "Save mappings"
<br/>![](01/53.png)

5. Save the Google DNS zones into your domain name configuration 
<br/>![](01/54.png)

6. The format should look like this in your registrar
<br/>![](01/55.png)

---

[Go back](../../README.md)
