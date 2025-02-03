# hello-world-php-app

This repository provides a Helm Chart for deploying hello-world-php-app in a Kubernetes environment using production-grade Kubernetes manifests. 
The goal of this project is to demonstrate best practices for deploying containerized applications.

## **Application Endpoints**
The application exposes three endpoints:

| **Endpoint**     | **Description**                                                          |
|------------------|--------------------------------------------------------------------------|
| `/v1/greeting`   | Returns `"Hello World"` and a unique visitor counter                     |
| `/healthz/live`  | Liveness probe - checks if the application is running                    |
| `/healthz/ready` | Readiness probe - checks if the application is ready to receive requests |

---

## 🚀 Getting Started

### **Prerequisites**
Before deploying this application, ensure you have the following tools installed:

- **[Docker](https://docs.docker.com/get-docker/)** – Required for building and pushing the application image.
- **[Helm](https://helm.sh/docs/intro/install/)** – Used for managing Kubernetes deployments.
- **[Kubectl](https://kubernetes.io/docs/tasks/tools/)** – CLI for interacting with Kubernetes clusters.
- **A running Kubernetes cluster** – e.g., Minikube, KIND, or a managed Kubernetes service (EKS, AKS, GKE).

### Build and Push the Docker Image
Before deploying the application, build and push the container image to your registry.

```bash
docker build -t <your-registry>/hello-world-php-app:latest .
docker push <your-registry>/hello-world-php-app:latest
```

### Adjust default helm values to your needs  
Save the modified values as a separate file, e.g., my-values.yaml.

### Deploy an application
Use Helm to install the chart in your Kubernetes cluster with your custom values.

```bash
helm install hello-world charts/hello-world-php-app/ -f my-values.yaml
```

## 🔧 Development & Local Testing

For local testing and development, you can use **KIND** (Kubernetes in Docker) along with **Taskfile** to automate the setup and deployment process.

### Additional Prerequisites
In addition to the general prerequisites, you need:

- **[Task](https://taskfile.dev/installation/)** – Task runner for automating commands.
- **[KIND](https://kind.sigs.k8s.io/docs/user/quick-start/)** – Kubernetes in Docker for local cluster testing.
- **[Docker Compose](https://docs.docker.com/compose/)** – If you want to test using a local containerized environment.

### Available Tasks

This project provides a Taskfile to automate the local development and testing process.
```bash
task --list

task: Available tasks for this project:
* build-image:          Build hello-world-php-app image with static tag
* compose-down:         Destroy local test environment and remove volumes
* compose-up:           Start local test environment using docker-compose
* helm-deploy:          Deploy Helm chart to KIND cluster and run tests
* helm-uninstall:       Uninstall test-app helm release
* kind-setup:           Setup a KIND cluster with Ingress Controller
* kind-teardown:        Destroy the KIND cluster
* test-curl:            Execute a test request verifying that the service is available through the ingress controller

```

To execute a task, simply run:
```bash
task <task-name>
```

### 💡 Ideas for Improvement

Below are some ideas for improvements and potential enhancements:
- **Fine-tune default container resources** – Current CPU and memory limits should be adjusted based on real-world load testing.
- **Enhance HPA with external metrics** – Instead of relying solely on CPU utilization, consider using external metrics (e.g., request rate, response time).
- **Secure MySQL credentials** – Instead of using environment variables, the application should retrieve credentials from a mounted file (e.g., Kubernetes Secret volume).
- **Restrict WebApp Egress Traffic** – Instead of allowing unrestricted egress, WebApp should only be permitted to communicate with Redis and MySQL endpoints.
- **Add documentation to the Helm Chart** – Improve usability by including a `README.md` inside the Helm Chart with instructions on values, deployment options, and customization.