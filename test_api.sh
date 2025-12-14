#!/bin/bash

# Script de test pour l'API Unity
# Usage: ./test_api.sh

API_URL="http://localhost:8319/api/unity"

echo "================================================"
echo "🧪 Test de l'API Claw Machine Unity"
echo "================================================"
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test 1: Ping
echo -e "${YELLOW}1. Test Ping${NC}"
echo "GET $API_URL/ping"
echo ""
curl -s -X GET "$API_URL/ping" | jq .
echo ""
echo "---"
echo ""

# Test 2: Récupérer les settings par défaut
echo -e "${YELLOW}2. Test Settings (défaut)${NC}"
echo "GET $API_URL/settings/default"
echo ""
curl -s -X GET "$API_URL/settings/default" | jq .
echo ""
echo "---"
echo ""

# Test 3: Récupérer settings avec ID
echo -e "${YELLOW}3. Test Settings (ID=1)${NC}"
echo "GET $API_URL/settings/1"
echo ""
curl -s -X GET "$API_URL/settings/1" | jq .
echo ""
echo "---"
echo ""

# Test 4: Envoyer un score (sans hash, mode dev)
echo -e "${YELLOW}4. Test POST Score (sans hash)${NC}"
echo "POST $API_URL/score"
echo ""
curl -s -X POST "$API_URL/score" \
  -H "Content-Type: application/json" \
  -d '{
    "playerName": "TestPlayer",
    "score": 1500,
    "duration": 60.5
  }' | jq .
echo ""
echo "---"
echo ""

# Test 5: Envoyer un score invalide (score négatif)
echo -e "${YELLOW}5. Test Score Invalide (devrait échouer)${NC}"
echo "POST $API_URL/score"
echo ""
curl -s -X POST "$API_URL/score" \
  -H "Content-Type: application/json" \
  -d '{
    "playerName": "BadPlayer",
    "score": -100,
    "duration": 30.0
  }' | jq .
echo ""
echo "---"
echo ""

# Test 6: Récupérer le leaderboard
echo -e "${YELLOW}6. Test Leaderboard (top 5)${NC}"
echo "GET $API_URL/leaderboard?limit=5"
echo ""
curl -s -X GET "$API_URL/leaderboard?limit=5" | jq .
echo ""
echo "---"
echo ""

echo -e "${GREEN}✅ Tests terminés !${NC}"
echo ""
echo "Pour plus d'infos, consulte UNITY_API.md"
