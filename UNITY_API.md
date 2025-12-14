# Documentation API Unity - Claw Machine

API REST pour l'intégration avec Unity. Endpoints simplifiés et optimisés pour le jeu.

## 🌐 URL de Base

```
http://localhost:8319/api/unity
```

## 📋 Sommaire

- [Endpoints](#endpoints)
  - [Ping](#ping)
  - [Récupérer les paramètres](#récupérer-les-paramètres)
  - [Enregistrer un score](#enregistrer-un-score)
  - [Leaderboard](#leaderboard)
- [Structures de données](#structures-de-données)
- [Codes d'erreur](#codes-derreur)
- [Intégration Unity](#intégration-unity)

---

## Endpoints

### Ping

Vérifier que l'API fonctionne.

**Endpoint:** `GET /api/unity/ping`

**Réponse:**
```json
{
  "success": true,
  "message": "Claw Machine API is running",
  "timestamp": "2024-12-14 02:30:15",
  "environment": "dev"
}
```

---

### Récupérer les paramètres

Obtenir les paramètres de jeu depuis le serveur.

#### Paramètres par défaut (ID 1)

**Endpoint:** `GET /api/unity/settings/default`

**Réponse:**
```json
{
  "id": 1,
  "clawSpeed": 5.0,
  "timeLimit": 60,
  "difficulty": "medium",
  "itemSpawnRate": 2.5
}
```

#### Paramètres spécifiques

**Endpoint:** `GET /api/unity/settings/{id}`

**Paramètres:**
- `id` (int) : ID des paramètres à récupérer

**Réponse:** Identique à celle par défaut

**Erreur 404:**
```json
{
  "error": "Settings not found",
  "message": "Les paramètres avec l'ID 5 n'existent pas"
}
```

---

### Enregistrer un score

Envoyer un score après une partie.

**Endpoint:** `POST /api/unity/score`

**Headers:**
- `Content-Type: application/json`

**Body:**
```json
{
  "playerName": "Player1",
  "score": 1500,
  "duration": 60.5,
  "hash": "abc123def456..."
}
```

**Champs:**
- `playerName` (string, requis) : Nom du joueur (1-50 caractères, alphanumériques + espaces, tirets, underscores)
- `score` (int, requis) : Score obtenu (0-999999)
- `duration` (float, requis) : Durée de la partie en secondes (max 3600s)
- `hash` (string, optionnel en dev) : Hash de sécurité généré par Unity
- `playedAt` (string, optionnel) : Timestamp ISO 8601, généré automatiquement si absent

**Réponse (201 Created):**
```json
{
  "success": true,
  "message": "Score enregistré avec succès",
  "data": {
    "id": 42,
    "playerName": "Player1",
    "score": 1500,
    "duration": 60.5,
    "playedAt": "2024-12-14 02:35:22"
  }
}
```

**Erreurs possibles:**

**400 - Validation échouée:**
```json
{
  "error": "Validation failed",
  "message": "Les données envoyées sont invalides",
  "errors": {
    "playerName": "Le nom du joueur est requis",
    "score": "Le score doit être positif ou zéro"
  }
}
```

**400 - Score implausible:**
```json
{
  "error": "Implausible score",
  "message": "Le score semble anormal par rapport à la durée de jeu"
}
```

**401 - Hash invalide:**
```json
{
  "error": "Invalid hash",
  "message": "Le hash de sécurité est invalide"
}
```

---

### Leaderboard

Récupérer le classement des meilleurs scores.

**Endpoint:** `GET /api/unity/leaderboard?limit=10`

**Paramètres (query):**
- `limit` (int, optionnel) : Nombre de scores à retourner (défaut: 10, max: 100)

**Réponse:**
```json
{
  "success": true,
  "count": 10,
  "scores": [
    {
      "id": 42,
      "playerName": "ProGamer",
      "score": 9500,
      "duration": 120.5,
      "playedAt": "2024-12-14 02:30:15"
    },
    {
      "id": 38,
      "playerName": "Player2",
      "score": 7800,
      "duration": 95.2,
      "playedAt": "2024-12-14 01:15:30"
    }
  ]
}
```

---

## Structures de données

### GameSettings (C#)

```csharp
[Serializable]
public class GameSettings
{
    public int id;
    public float clawSpeed;
    public int timeLimit;
    public string difficulty;
    public float itemSpawnRate;
}
```

### Score (C#)

```csharp
[Serializable]
public class Score
{
    public int id;
    public string playerName;
    public int score;
    public float duration;
    public string playedAt;
}
```

---

## Codes d'erreur

| Code | Description |
|------|-------------|
| 200  | Succès (GET) |
| 201  | Créé avec succès (POST) |
| 400  | Requête invalide (validation échouée, données incorrectes) |
| 401  | Non autorisé (hash invalide) |
| 404  | Ressource non trouvée |
| 500  | Erreur serveur |

---

## Intégration Unity

### Configuration

Dans `NetworkManager.cs`, l'URL de base est déjà configurée :

```csharp
private const string SYMFONY_API_BASE_URL = "http://localhost:8319/api/unity";
```

### Exemple d'utilisation

#### Récupérer les paramètres

```csharp
StartCoroutine(NetworkManager.Instance.GetGameSettings(
    settingsId: 1,
    onSuccess: (settings) => {
        Debug.Log($"TimeLimit: {settings.timeLimit}s");
        Debug.Log($"Difficulty: {settings.difficulty}");
    },
    onError: (error) => {
        Debug.LogError($"Erreur: {error}");
    }
));
```

#### Envoyer un score

```csharp
string hash = ScoreValidator.GenerateScoreHash(playerName, score, duration, secretKey);

StartCoroutine(NetworkManager.Instance.PostScore(
    playerName: "Player1",
    score: 1500,
    duration: 60.5f,
    hash: hash,
    onSuccess: (scoreData) => {
        Debug.Log($"Score enregistré ! ID: {scoreData.id}");
    },
    onError: (error) => {
        Debug.LogError($"Erreur: {error}");
    }
));
```

#### Récupérer le leaderboard

```csharp
StartCoroutine(NetworkManager.Instance.GetTopScores(
    limit: 10,
    onSuccess: (scores) => {
        foreach (var score in scores)
        {
            Debug.Log($"{score.playerName}: {score.score} pts");
        }
    },
    onError: (error) => {
        Debug.LogError($"Erreur: {error}");
    }
));
```

---

## Sécurité

### Hash de validation

Pour éviter la triche, chaque score doit être accompagné d'un hash de sécurité :

```csharp
string hash = ScoreValidator.GenerateScoreHash(playerName, score, duration, secretKey);
```

Le serveur validera ce hash avant d'enregistrer le score.

**En mode développement**, le hash est optionnel pour faciliter les tests.

**En production**, le hash est obligatoire.

### Sanitization

Les noms de joueurs sont automatiquement nettoyés côté serveur :
- Caractères autorisés : `a-z A-Z 0-9 _ - espace`
- Longueur : 1-50 caractères
- Les scripts HTML/JS sont supprimés

### Validation des scores

Le serveur vérifie automatiquement :
- Plausibilité du score par rapport à la durée
- Cohérence des données
- Validité du hash (si fourni)

---

## Notes

- Toutes les réponses sont en JSON
- Les timestamps utilisent le format : `Y-m-d H:i:s`
- L'API supporte CORS pour les requêtes depuis Unity
- Le serveur génère automatiquement `playedAt` si non fourni
- Les scores sont triés par ordre décroissant dans le leaderboard

---

## Support

Pour toute question ou problème :
1. Vérifier que l'API répond avec `GET /api/unity/ping`
2. Consulter les logs Symfony : `var/log/dev.log`
3. Vérifier les logs Unity dans la console

