INSERT INTO menu (rang, libelle, url, role, parent_id) VALUES
('001', 'Accueil', 'app_accueil', 'ROLE_USER', null),
('002', 'Article', 'app_article_index', 'ROLE_USER', null),
('003', 'Client', 'app_client', 'ROLE_USER', null),
('004', 'Commande', 'app_commande', 'ROLE_USER', null),
('005', 'Parametre', 'app_parametre', 'ROLE_USER', null),
('006', 'Facture', 'app_facture', 'ROLE_USER', 4),
('007', 'Devis', 'app_accueil', 'ROLE_USER', 4),
('008', 'Avoir', 'app_accueil', 'ROLE_USER', 4),
('009', 'User', 'app_user', 'ROLE_USER', 5),
('010', 'Role', 'app_role', 'ROLE_USER', 5),
('011', 'Menu', 'app_menu', 'ROLE_USER', 5);

