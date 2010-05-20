CREATE TABLE gesseh_critere (id BIGINT AUTO_INCREMENT, form BIGINT NOT NULL, titre VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, ratio BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX form_idx (form), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_etudiant (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, promo_id BIGINT NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX promo_id_idx (promo_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_eval (id BIGINT AUTO_INCREMENT, stage_id BIGINT NOT NULL, critere_id BIGINT NOT NULL, valeur VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX stage_id_idx (stage_id), INDEX critere_id_idx (critere_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_hopital (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL UNIQUE, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255), web VARCHAR(255), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_param (id BIGINT AUTO_INCREMENT, titre VARCHAR(255) NOT NULL, valeur VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_periode (id BIGINT AUTO_INCREMENT, debut DATE NOT NULL, fin DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_promo (id BIGINT AUTO_INCREMENT, titre VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_stage (id BIGINT AUTO_INCREMENT, terrain_id BIGINT NOT NULL, periode_id BIGINT NOT NULL, etudiant_id BIGINT NOT NULL, is_active TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX terrain_id_idx (terrain_id), INDEX periode_id_idx (periode_id), INDEX etudiant_id_idx (etudiant_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE gesseh_terrain (id BIGINT AUTO_INCREMENT, hopital_id BIGINT NOT NULL, filiere VARCHAR(255) NOT NULL, patron VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, form_id BIGINT DEFAULT 1 NOT NULL, is_active TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX hopital_id_idx (hopital_id), INDEX form_id_idx (form_id), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE gesseh_etudiant ADD CONSTRAINT gesseh_etudiant_promo_id_gesseh_promo_id FOREIGN KEY (promo_id) REFERENCES gesseh_promo(id) ON DELETE CASCADE;
ALTER TABLE gesseh_eval ADD CONSTRAINT gesseh_eval_stage_id_gesseh_stage_id FOREIGN KEY (stage_id) REFERENCES gesseh_stage(id) ON DELETE CASCADE;
ALTER TABLE gesseh_eval ADD CONSTRAINT gesseh_eval_critere_id_gesseh_critere_id FOREIGN KEY (critere_id) REFERENCES gesseh_critere(id) ON DELETE CASCADE;
ALTER TABLE gesseh_stage ADD CONSTRAINT gesseh_stage_terrain_id_gesseh_terrain_id FOREIGN KEY (terrain_id) REFERENCES gesseh_terrain(id) ON DELETE CASCADE;
ALTER TABLE gesseh_stage ADD CONSTRAINT gesseh_stage_periode_id_gesseh_periode_id FOREIGN KEY (periode_id) REFERENCES gesseh_periode(id) ON DELETE CASCADE;
ALTER TABLE gesseh_stage ADD CONSTRAINT gesseh_stage_etudiant_id_gesseh_etudiant_id FOREIGN KEY (etudiant_id) REFERENCES gesseh_etudiant(id) ON DELETE CASCADE;
ALTER TABLE gesseh_terrain ADD CONSTRAINT gesseh_terrain_hopital_id_gesseh_hopital_id FOREIGN KEY (hopital_id) REFERENCES gesseh_hopital(id) ON DELETE CASCADE;
ALTER TABLE gesseh_terrain ADD CONSTRAINT gesseh_terrain_form_id_gesseh_critere_form FOREIGN KEY (form_id) REFERENCES gesseh_critere(form);
