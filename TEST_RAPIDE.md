# Tests Rapides de l'API

## Prérequis

1. **Démarrer le serveur Symfony** (si pas déjà fait) :
```bash
php bin/console server:start
# OU
symfony serve -d
# OU
php -S localhost:8319 -t public
```

2. **Vérifier que la base de données est à jour** :
```bash
php bin/console doctrine:migrations:migrate
```

## Tests avec curl

### 1. Test Ping (vérifier que l'API fonctionne)

```bash
curl http://localhost:8319/api/unity/ping
```

**Réponse attendue :**
```json
{
  "success": true,
  "message": "Claw Machine API is running",
  "timestamp": "2024-12-14 02:30:15",
  "environment": "dev"
}
```

---

### 2. Récupérer les paramètres par défaut

```bash
curl http://localhost:8319/api/unity/settings/default
```

**Réponse attendue :**
```json
{
  "id": 1,
  "clawSpeed": 5.0,
  "timeLimit": 60,
  "difficulty": "medium",
  "itemSpawnRate": 2.5
}
```

---

### 3. Envoyer un score (mode dev, sans hash)

```bash
curl -X POST http://localhost:8319/api/unity/score \
  -H "Content-Type: application/json" \
  -d '{
    "playerName": "TestPlayer",
    "score": 1500,
    "duration": 60.5
  }'
```

**Réponse attendue :**
```json
{
  "success": true,
  "message": "Score enregistré avec succès",
  "data": {
    "id": 1,
    "playerName": "TestPlayer",
    "score": 1500,
    "duration": 60.5,
    "playedAt": "2024-12-14 02:35:22"
  }
}
```

---

### 4. Récupérer le leaderboard

```bash
curl "http://localhost:8319/api/unity/leaderboard?limit=5"
```

**Réponse attendue :**
```json
{
  "success": true,
  "count": 5,
  "scores": [
    {
      "id": 1,
      "playerName": "TestPlayer",
      "score": 1500,
      "duration": 60.5,
      "playedAt": "2024-12-14 02:35:22"
    }
  ]
}
```

---

## Test avec le script automatique

Si tu as `jq` installé (pour formatter le JSON) :

```bash
./test_api.sh
```

Sinon, installe jq :
```bash
sudo apt install jq
```

---

## Test depuis Unity

### Dans l'éditeur Unity

1. Ouvre ta scène de jeu
2. Assure-toi que le `NetworkManager` est dans la scène
3. Lance le jeu (Play)
4. Regarde la console Unity pour les logs

### Test manuel avec un bouton

Crée un script de test :

```csharp
using UnityEngine;
using UnityEngine.UI;

public class ApiTester : MonoBehaviour
{
    public Button testButton;

    void Start()
    {
        testButton.onClick.AddListener(TestAPI);
    }

    void TestAPI()
    {
        // Test 1: Ping
        Debug.Log("=== TEST PING ===");
        StartCoroutine(TestPing());

        // Test 2: Get Settings
        Debug.Log("=== TEST SETTINGS ===");
        StartCoroutine(TestSettings());

        // Test 3: Post Score
        Debug.Log("=== TEST POST SCORE ===");
        StartCoroutine(TestScore());

        // Test 4: Get Leaderboard
        Debug.Log("=== TEST LEADERBOARD ===");
        StartCoroutine(TestLeaderboard());
    }

    IEnumerator TestPing()
    {
        // Ping n'est pas implémenté dans NetworkManager actuel
        // Utilise UnityWebRequest directement
        using (UnityEngine.Networking.UnityWebRequest www =
               UnityEngine.Networking.UnityWebRequest.Get("http://localhost:8319/api/unity/ping"))
        {
            yield return www.SendWebRequest();
            if (www.result == UnityEngine.Networking.UnityWebRequest.Result.Success)
            {
                Debug.Log("✅ PING OK: " + www.downloadHandler.text);
            }
            else
            {
                Debug.LogError("❌ PING FAILED: " + www.error);
            }
        }
    }

    IEnumerator TestSettings()
    {
        yield return NetworkManager.Instance.GetGameSettings(
            1,
            onSuccess: (settings) => {
                Debug.Log($"✅ SETTINGS OK: TimeLimit={settings.timeLimit}s, Difficulty={settings.difficulty}");
            },
            onError: (error) => {
                Debug.LogError($"❌ SETTINGS FAILED: {error}");
            }
        );
    }

    IEnumerator TestScore()
    {
        yield return NetworkManager.Instance.PostScore(
            "TestPlayer",
            1500,
            60.5f,
            null, // Pas de hash en mode dev
            onSuccess: (score) => {
                Debug.Log($"✅ SCORE OK: ID={score.id}, Score={score.score}");
            },
            onError: (error) => {
                Debug.LogError($"❌ SCORE FAILED: {error}");
            }
        );
    }

    IEnumerator TestLeaderboard()
    {
        yield return NetworkManager.Instance.GetTopScores(
            5,
            onSuccess: (scores) => {
                Debug.Log($"✅ LEADERBOARD OK: {scores.Count} scores");
                foreach (var s in scores)
                {
                    Debug.Log($"  - {s.playerName}: {s.score} pts");
                }
            },
            onError: (error) => {
                Debug.LogError($"❌ LEADERBOARD FAILED: {error}");
            }
        );
    }
}
```

---

## Dépannage

### Le serveur ne répond pas

1. Vérifier que le serveur tourne :
   ```bash
   ps aux | grep php
   ```

2. Vérifier les logs Symfony :
   ```bash
   tail -f var/log/dev.log
   ```

3. Tester avec une URL simple :
   ```bash
   curl http://localhost:8319
   ```

### Erreur 404

- Vérifier que l'URL est correcte : `/api/unity/...`
- Vérifier que les routes sont bien chargées :
  ```bash
  php bin/console debug:router | grep unity
  ```

### Erreur 500

- Consulter les logs :
  ```bash
  tail -f var/log/dev.log
  ```

### Base de données vide

- Créer des données de test :
  ```bash
  php bin/console doctrine:fixtures:load
  ```

---

## Résumé des URLs

| Endpoint | Méthode | URL |
|----------|---------|-----|
| Ping | GET | http://localhost:8319/api/unity/ping |
| Settings (default) | GET | http://localhost:8319/api/unity/settings/default |
| Settings (ID) | GET | http://localhost:8319/api/unity/settings/1 |
| Post Score | POST | http://localhost:8319/api/unity/score |
| Leaderboard | GET | http://localhost:8319/api/unity/leaderboard?limit=10 |
