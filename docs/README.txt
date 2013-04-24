README
======

This directory should be used to place project specfic documentation including
but not limited to project notes, generated API/phpdoc documentation, or
manual files generated or hand written.  Ideally, this directory would remain
in your development environment only and should not be deployed with your
application to it's final production location.


Setting Up Your VHOST
=====================

The following is a sample VHOST you might want to consider for your project.

<VirtualHost *:80>
   DocumentRoot "/home/xu/Workspace/rkd/public"
   ServerName rkd.local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development

   <Directory "/home/xu/Workspace/rkd/public">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>

</VirtualHost>

http://jqueryui.com/download/#!zThemeParams=5d000001000006000000000000003d8888d844329a8dfe02723de3e57025317a2a65782a165ef8177f635969236a59e739429ef67577a89d0f313f387ec3de49ba975ca77012890cd91ab28dd2e8739baf92770c93b2bc1b11debaf8995f20be46a0981e79e30079e34910213212c552d2fa77b8f286b9fb688798d359df3f5fa2e915a3c95ecd3562899c505999704ef86bbc67eed351dd00d32046c958076fad9b4ff2e27c175ff42351b3521bd579f9cfc1f6c2df153b67e010fd1e668c2db0f90fad7346cdabef9dc52e6ee35ac53bd75b4d33b815528a48dc458889804b1753168319699add26fcc4b29716e17f0ef0f13e2a4de405c967b8effd2a35a2b3b07a7ced80f3a579187d472cc7431d58fe1801852a144960541e1d17ebebcd5399b539562ab1854b9f578ca0c8020440edadf9a97b33a5b60ae6ea700c3e21d2c369df1ba205361b2413d599e06d6de2e4460d19631f41a994ed2ecace1f1d476933dd1750f911548fbdf5f1aeb38892d068f312a9433202991bf3735ad0a52d826c418b505bcfc0e69419d8f1abf173ba46c097263bba863a0ea82d1d860ece34e50618b8aa7a8817e492d5dbe435946636e7b99df8fb038b12b6f660c1c8bffedc27000e7d75051e2586c100a17b264637e5fcd74753
