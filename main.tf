terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "5.74.0"
    }
  }
  required_version = ">= 1.9.0"
}

provider "aws" {
  region = var.region_virginia
}

#Nuevo grupo de seguridad para ssh y http
resource "aws_security_group" "web" {
  name = "aseguradora_sg"
  description = "Grupo de seguridad que permite ssh y http"
  vpc_id = aws_vpc.default.id

  ingress {
    from_port = 22
    to_port = 22
    protocol = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port = 80
    to_port = 80
    protocol = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port = 0
    to_port = 0
    protocol = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# Se le da la clave publica a aws
resource "aws_key_pair" "clavepublica" {
  key_name   = "aseguradora_aws_key"
  public_key = file("./id_rsa.pub")
}

#Nueva instancia EC2
resource "aws_instance" "aseguradora" {
    ami = "ami-06b21ccaeff8cd686" #Ami de Amazon Linux 2023
    instance_type = "t2.micro"
    vpc_security_group_ids = [aws_security_group.web.id]
    subnet_id = aws_subnet.public[0].id
    key_name = aws_key_pair.clavepublica.key_name #se referencia a la clave publica nunca la privada
    user_data = file("script.sh") #Donde almaceno el script que se ejecutara en la instancia
  tags = {
    Name = "LinuxAseguradora"
  }
}

# VPC resources

resource "aws_vpc" "default" {
  cidr_block           = var.cidr_block
  enable_dns_support   = true
  enable_dns_hostnames = true
}

resource "aws_internet_gateway" "default" {
  vpc_id = aws_vpc.default.id
}

resource "aws_route_table" "private" {
  count = length(var.private_subnet_cidr_blocks)

  vpc_id = aws_vpc.default.id
}

resource "aws_route" "private" {
  count = length(var.private_subnet_cidr_blocks)

  route_table_id         = aws_route_table.private[count.index].id
  destination_cidr_block = "0.0.0.0/0"
  nat_gateway_id         = aws_nat_gateway.default[count.index].id
}

resource "aws_route_table" "public" {
  vpc_id = aws_vpc.default.id
}

resource "aws_route" "public" {
  route_table_id         = aws_route_table.public.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.default.id
}

resource "aws_subnet" "private" {
  count = length(var.private_subnet_cidr_blocks)

  vpc_id            = aws_vpc.default.id
  cidr_block        = var.private_subnet_cidr_blocks[count.index]
  availability_zone = "us-east-1a"
}

resource "aws_subnet" "public" {
  count = length(var.public_subnet_cidr_blocks)

  vpc_id                  = aws_vpc.default.id
  cidr_block              = var.public_subnet_cidr_blocks[count.index]
  availability_zone       = "us-east-1a"
  map_public_ip_on_launch = true
}


resource "aws_eip" "nat" {
  count = length(var.public_subnet_cidr_blocks)

  vpc = true
}

resource "aws_nat_gateway" "default" {
  depends_on = ["aws_internet_gateway.default"]

  count = length(var.public_subnet_cidr_blocks)

  allocation_id = aws_eip.nat[count.index].id
  subnet_id     = aws_subnet.public[count.index].id
}

terraform {
  backend "s3" {
    bucket = "proyectoaseguradora-tfstate-bucket" 
    key    = "terraform.tfstate"                   # Ruta del archivo tfstate en el bucket
    region = var.region_virginia                          
  }
}


# S3 para el tfstate
resource "aws_s3_bucket" "terraform_state" {
  bucket = "proyectoaseguradora-tfstate-bucket" 

  # Habilita el versionado para mantener historial del tfstate
  versioning {
    enabled = true 
  }

# Evita la destrucción accidental del bucket
  lifecycle {
    prevent_destroy = true  
  }
}
resource "aws_s3_bucket_public_access_block" "public_access_block" {
  bucket = aws_s3_bucket.terraform_state.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}
 