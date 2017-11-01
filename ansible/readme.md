# Ansible

## Install roles

```
$ ansible-galaxy install -r ansible/requirements.yml -p ansible/roles
```

## Provision production

You need HY username and password for this.

```
$ ansible-playbook -i ansible/inventory/production ansible/provision.yml --ask-become-pass -u USERNAME
```

## Deploy Drupal 8 application to production

```
$ ansible-playbook -i ansible/inventory/production ansible/deploy.yml --ask-become-pass -u USERNAME
```
