CREATE TABLE "notas" (
	"id_nota"	INTEGER NOT NULL UNIQUE,
	"hash"	TEXT NOT NULL UNIQUE,
	"salt"	TEXT NOT NULL,
	"gerais"	INTEGER NOT NULL,
	"fase2"	TEXT NOT NULL,
	"redacao"	REAL NOT NULL,
	"media"	REAL NOT NULL,
	"cod_carreira"	INTEGER NOT NULL,
	"nome_carreira"	TEXT NOT NULL,
	"cod_curso"	INTEGER NOT NULL,
	"nome_curso"	TEXT NOT NULL,
	"num_chamada"	INTEGER NOT NULL,
	"tipo_chamada"	TEXT NOT NULL,
	"ano"	INTEGER NOT NULL,
	PRIMARY KEY("id_nota" AUTOINCREMENT)
);

CREATE TABLE "config" (
	"key"	TEXT NOT NULL UNIQUE,
	"value"	TEXT,
	PRIMARY KEY("key")
);

INSERT INTO config (key, value) VALUES ("grecaptcha_sitekey", NULL);
INSERT INTO config (key, value) VALUES ("grecaptcha_secretkey", NULL);
