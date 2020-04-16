#!/usr/bin/env python3

import sys, random, hashlib, json

base = {
  "hash": None,
  "salt": None,
  "gerais": None,
  "fase2": None,
  "redacao": None,
  "media": None,
  "cod_carreira": None,
  "nome_carreira": None,
  "cod_curso": None,
  "nome_curso": None,
  "num_chamada": None,
  "tipo_chamada": None,
  "ano": None,
}

def falhou():
  print("[]");
  sys.exit()

def shash(segredo, salt):
  return hashlib.pbkdf2_hmac("sha512", segredo.encode("utf-8"),
                             salt.encode("utf-8"), 100000).hex()

def testador(ano, login, senha):
  base["salt"] = "teste"
  base["gerais"] = int(random.uniform(0, 90))
  base["fase2"] = [int(random.uniform(0, 100)), int(random.uniform(0, 100))]
  base["redacao"] = int(random.uniform(0, 50))
  base["media"] = int(random.uniform(0, 1000))
  base["cod_carreira"] = int(random.uniform(100, 800))
  base["cod_curso"] = int(random.uniform(1, 20))
  base["nome_carreira"] = "Carreira de Teste #" + str(base["cod_carreira"])
  base["nome_curso"] = "Curso de Teste #" + str(base["cod_curso"])
  base["num_chamada"] = int(random.uniform(1, 4))
  base["tipo_chamada"] = random.choice(["chamada", "lista de espera"])
  base["ano"] = ano
  base["hash"] = shash(login + ".", base["salt"])


extratores = {
  "teste": testador,
  "2020": lambda a, b, c: []
}

if __name__ == "__main__":
  if len(sys.argv) != 4:
    falhou()
  ano = sys.argv[1]
  login = sys.argv[2]
  senha = sys.argv[3]
  if ano not in extratores:
    falhou()
  extratores[ano](ano, login, senha)
  print(json.dumps(base))

