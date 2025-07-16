#!/bin/bash

# This script runs after deployment to optimize Laravel
# It should be called via a webhook or manually after deployment

# Note: Since Vercel functions are stateless, we can't cache config/routes
# So we'll configure Laravel to work without caching in production

echo "Laravel optimization skipped - using stateless configuration for Vercel"
